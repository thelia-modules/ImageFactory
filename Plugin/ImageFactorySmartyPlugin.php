<?php
/*************************************************************************************/
/*      This file is part of the module ImageFactory.                                */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/
namespace ImageFactory\Plugin;

use ImageFactory\Entity\FactoryEntity;
use ImageFactory\Handler\FactoryHandler;
use ImageFactory\Response\FactoryResponse;
use ImageFactory\Response\FactoryResponseCollection;
use ImageFactory\Util\PathInfo;
use Propel\Runtime\ActiveQuery\Criteria;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\Map\ProductSaleElementsProductImageTableMap;
use Thelia\Model\Tools\ModelCriteriaTools;
use Thelia\Tools\URL;
use TheliaSmarty\Template\AbstractSmartyPlugin;
use TheliaSmarty\Template\SmartyPluginDescriptor;

/**
 * Class ImageFactorySmartyPlugin
 * @package ImageFactory\Plugin
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class ImageFactorySmartyPlugin extends AbstractSmartyPlugin
{
    // list arguments
    const ARG_CODE = 'code';
    const ARG_VIEW = 'view';
    const ARG_VIEW_ID = 'view_id';
    const ARG_STRICT_MODE = 'strict_mode';
    const ARG_FORCE_NOT_FOUND = 'force_not_found';
    const ARG_IMAGE_ID = 'image_id';
    const ARG_FILE_NAME = 'file_name';
    const ARG_LIMIT = 'limit';
    const ARG_ATTR = 'attr';
    const ARG_INNER = 'inner';
    const ARG_OUT = 'out';
    const ARG_DISABLE_I18N_PROCESSING = 'disable_i18n_processing';

    /** @var FactoryHandler */
    protected $factoryHandler;

    /** @var URL */
    protected $URL;

    /** @var RequestStack */
    protected $requestStack;

    /** @var string */
    protected static $locale;

    /**
     * ImageFactorySmartyPlugin constructor.
     * @param FactoryHandler $factoryHandler
     * @param URL $url
     * @param RequestStack $requestStack
     */
    public function __construct(FactoryHandler $factoryHandler, URL $url, RequestStack $requestStack)
    {
        $this->factoryHandler = $factoryHandler;
        $this->URL = $url;
        $this->requestStack = $requestStack;
    }

    /**
     * @param array $params
     * @param \Smarty_Internal_Template $smarty
     * @return string
     */
    public function generateHTML(array $params, \Smarty_Internal_Template &$smarty)
    {
        $code = $this->getParam($params, self::ARG_CODE, '');

        if (empty($code)) {
            throw new \InvalidArgumentException('Invalid argument code, please specify a factory code.');
        }

        $params[self::ARG_ATTR] = !isset($params[self::ARG_ATTR]) ? [] : $params[self::ARG_ATTR];

        $factory = $this->getFactoryResolver()->getByCode($this->getParam($params, self::ARG_CODE));

        if (null !== $disableI18nProcessing = $this->getParam($params, self::ARG_DISABLE_I18N_PROCESSING)) {
            $factory->setDisableI18nProcessing((bool) $disableI18nProcessing);
        }

        $factoryResponseCollection = new FactoryResponseCollection();

        if (null !== $this->getParam($params, self::ARG_FILE_NAME)) {
             $this->resolveByFileName($factory, $factoryResponseCollection, $params);
        } elseif (null !== $this->getParam($params, self::ARG_VIEW)) {
            if (null !== $this->getParam($params, self::ARG_VIEW_ID)) {
                $this->resolveByViewId($factory, $factoryResponseCollection, $params);
            } elseif (null !== $this->getParam($params, self::ARG_IMAGE_ID)) {
                $this->resolveByImageId($factory, $factoryResponseCollection, $params);
            }
        }

        if (!count($factoryResponseCollection)) {
            $strictMode = (bool) $this->getParam($params, self::ARG_STRICT_MODE, false);
            if ($strictMode) {
                throw new \InvalidArgumentException('Invalid argument for smarty method image_factory');
            } elseif ((bool) $this->getParam($params, self::ARG_FORCE_NOT_FOUND, false)) {
                $this->generateImagesNotFound($factory, $factoryResponseCollection, $params);
            }
        }

        return $this->generateSmartyResponse($smarty, $factoryResponseCollection, $params);
    }

    /**
     * @param \Smarty_Internal_Template $smarty
     * @param FactoryResponseCollection $factoryResponseCollection
     * @param array $params
     * @return null|string
     */
    protected function generateSmartyResponse(
        \Smarty_Internal_Template &$smarty,
        FactoryResponseCollection $factoryResponseCollection,
        array $params
    ) {
        if (count($factoryResponseCollection)) {
            $html = [];

            foreach ($factoryResponseCollection as $factoryResponse) {
                $params[self::ARG_ATTR]['src'] = $this->URL->absoluteUrl(
                    '/' . $factoryResponse->getImageDestinationUri(),
                    [],
                    true
                );

                foreach ($params[self::ARG_ATTR] as $name => $attribute) {
                    $params[self::ARG_ATTR][$name] = $name . '="' . addcslashes($attribute, '"') . '"';
                }

                $factoryResponse->setHtml('<img ' . implode(' ', $params[self::ARG_ATTR]) . '/>');
                $html[] = $factoryResponse->getHtml();
            }

            // check if inner arg
            $inner = $this->getParam($params, self::ARG_INNER);

            if ($inner !== null && strpos($inner, '?') <= 0) {
                foreach ($html as $key => $image) {
                    $html[$key] = str_replace('?', $image, $inner);
                }
            }

            // check if out method
            if (null !== $out = $this->getParam($params, self::ARG_OUT, null)) {
                $smarty->assign($params[self::ARG_OUT], $factoryResponseCollection);
                return null;
            }

            // else render string method
            return implode("\r\n", $html);
        }

        return "";
    }

    /**
     * Example : {image_factory attr=['class'=> 'example-2'] code='test' view="product" image_id="10,11,12,13,14" inner="<li>?</li>"}
     * @param FactoryEntity $factory
     * @param FactoryResponseCollection $factoryResponseCollection
     * @param array $params
     */
    protected function resolveByImageId(
        FactoryEntity $factory,
        FactoryResponseCollection $factoryResponseCollection,
        array $params
    ) {
        $ids = explode(',', $this->getParam($params, self::ARG_IMAGE_ID));

        $modelCriteria = $this->getModelCriteriaByView($this->getParam($params, self::ARG_VIEW), $factory);
        $methodName = 'filterById';

        $modelCriteria->$methodName($ids,  Criteria::IN);

        if (null !== $limit = $this->getParam($params, self::ARG_LIMIT)) {
            $modelCriteria->limit($limit);
        }

        $imageModels = $modelCriteria->find();

        $this->generateImagesByModelCriteria($factory, $factoryResponseCollection, $params, $imageModels);
    }

    /**
     * Example : {image_factory attr=['class'=> 'example-1'] code='test' view="product" view_id="325" inner="<li>?</li>" limit=10}
     * @param FactoryEntity $factory
     * @param FactoryResponseCollection $factoryResponseCollection
     * @param array $params
     */
    protected function resolveByViewId(
        FactoryEntity $factory,
        FactoryResponseCollection $factoryResponseCollection,
        array $params
    ) {
        $view = $this->getParam($params, self::ARG_VIEW);
        $ids = explode(',', $this->getParam($params, self::ARG_VIEW_ID));

        $modelCriteria = $this->getModelCriteriaByView($view, $factory);
        $methodName = $this->getPropelMethodName($view);

        if ($view === 'product_sale_element') {
            $modelCriteria->addJoinCondition(
                ProductSaleElementsProductImageTableMap::TABLE_NAME,
                ProductSaleElementsProductImageTableMap::PRODUCT_SALE_ELEMENTS_ID . ' IN (?)',
                implode(',', $ids)
            );
        } else {
            $modelCriteria->$methodName($ids,  Criteria::IN);
        }

        if (null !== $limit = $this->getParam($params, self::ARG_LIMIT)) {
            $modelCriteria->limit($limit);
        }

        $imageModels = $modelCriteria->find();

        $this->generateImagesByModelCriteria($factory, $factoryResponseCollection, $params, $imageModels);
    }

    /**
     * Example : {image_factory attr=['class'=> 'example-3'] code='test' file_name="sample-image-394.png,sample-image-396.png" inner="<li>?</li>"}
     * @param FactoryEntity $factory
     * @param FactoryResponseCollection $factoryResponseCollection
     * @param array $params
     */
    protected function resolveByFileName(
        FactoryEntity $factory,
        FactoryResponseCollection $factoryResponseCollection,
        array $params
    ) {
        $fileNames = explode(',', $this->getParam($params, self::ARG_FILE_NAME));

        foreach ($fileNames as $fileName) {
            $pathInfo = new PathInfo($fileName);

            foreach ($factory->getSources() as $source) {
                if ($pathInfo->getFilename()) {
                    $path = $source . DS . $pathInfo->getFilename() . '.' . $pathInfo->getExtension();
                    if (file_exists($path)) {
                        $pathInfo = new PathInfo($path);
                        $factoryResponseCollection[] = $this->generateFactoryResponse(
                            $factory,
                            $params,
                            $pathInfo->getDirname() . DS . $pathInfo->getFilename() . '.' . $pathInfo->getExtension()
                        );
                        break;
                    }
                }
            }
        }
    }

    /**
     * @param string $view
     * @return string
     */
    protected function getPathByView($view)
    {
        return THELIA_LOCAL_DIR . 'media' . DS . 'images' . DS . strtolower($view);
    }

    /**
     * @param string $view
     * @return string
     */
    protected function getPropelMethodName($view)
    {
        return 'filterBy' . ucfirst($view) . 'Id';
    }

    /**
     * @param string $view
     * @param FactoryEntity $factory
     * @return \Thelia\Model\ProductImageQuery
     */
    protected function getModelCriteriaByView($view, FactoryEntity $factory)
    {
        if ($view === 'product_sale_element') {
            /** @var \Thelia\Model\ProductImageQuery $classQuery */
            $classQuery = '\Thelia\Model\ProductImageQuery';
            /** @var \Thelia\Model\ProductImage $classModel */
            $classModel = '\Thelia\Model\ProductImage';
        } else {
            /** @var \Thelia\Model\ProductImageQuery $classQuery */
            $classQuery = '\Thelia\Model\\' . ucfirst($view) . 'ImageQuery';
            /** @var \Thelia\Model\ProductImage $classModel */
            $classModel = '\Thelia\Model\\' . ucfirst($view) . 'Image';
        }

        if (!class_exists($classQuery) || !method_exists($classModel, 'getFile')) {
            throw new \InvalidArgumentException('invalid argument view');
        }

        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        if (static::$locale === null) {
            static::$locale = $request->getSession()->getLang()->getLocale();
        }

        $instance = $classQuery::create();

        if (!$factory->isDisableI18nProcessing()) {
            ModelCriteriaTools::getFrontEndI18n($instance, static::$locale, ['TITLE'], null, 'ID', true);
        }

        if ($view === 'product_sale_element') {
            $instance->useProductSaleElementsProductImageQuery(ProductSaleElementsProductImageTableMap::TABLE_NAME);
        }

        return $instance;
    }

    /**
     * @param FactoryEntity $factory
     * @param FactoryResponseCollection $factoryResponseCollection
     * @param array $params
     * @param \Thelia\Model\Product[] $models
     */
    protected function generateImagesByModelCriteria(
        FactoryEntity $factory,
        FactoryResponseCollection $factoryResponseCollection,
        array $params,
        $models
    ) {
        /** @var \Thelia\Model\ProductImage $model */
        foreach ($models as $model) {
            if ($model->hasVirtualColumn('i18n_title')) {
                $params[self::ARG_ATTR]['alt'] = $model->getVirtualColumn('i18n_title');
            }

            $factoryResponseCollection[] = $this->generateFactoryResponse(
                $factory,
                $params,
                $this->getPathByView($this->getParam($params, self::ARG_VIEW)) . DS . $model->getFile()
            );
        }
    }

    /**
     * @param FactoryEntity $factory
     * @param FactoryResponseCollection $factoryResponseCollection
     * @param array $params
     * @return FactoryResponse[]
     */
    protected function generateImagesNotFound(
        FactoryEntity $factory,
        FactoryResponseCollection $factoryResponseCollection,
        array $params
    ) {
        $nb = 1;
        for ($i = 0; $i < $nb; $i++) {
            $factoryResponseCollection[] = $this->generateFactoryResponse(
                $factory,
                $params,
                $factory->getImageNotFoundSourcePath()
            );
        }
    }

    /**
     * @param FactoryEntity $factory
     * @param array $params
     * @param null|string $fileSource
     * @return FactoryResponse
     */
    protected function generateFactoryResponse(
        FactoryEntity $factory,
        array $params,
        $fileSource = null
    ) {
        if ($fileSource !== null) {
            $pathInfo = new PathInfo($fileSource);

            $factory->setDisableProcessGenerate(true);
            return $this->getFactoryResolver()->resolveByFactoryAndImagePathInfo($factory, $pathInfo);
        }
    }

    /**
     * @return \ImageFactory\Resolver\FactoryResolver
     */
    protected function getFactoryResolver()
    {
        return$this->factoryHandler->getFactoryResolver();
    }

    /**
     * Define the various smarty plugins handled by this class
     *
     * @return array an array of smarty plugin descriptors
     */
    public function getPluginDescriptors()
    {
        return array(
            new SmartyPluginDescriptor('function', 'image_factory', $this, 'generateHTML')
        );
    }
}
