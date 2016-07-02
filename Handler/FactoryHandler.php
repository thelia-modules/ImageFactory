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
namespace ImageFactory\Handler;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Cache\FilesystemCache;
use ImageFactory\Entity\FactoryEntity;
use ImageFactory\Entity\FactoryEntityCollection;
use ImageFactory\Model\ImageFactory as ImageFactoryModel;
use ImageFactory\Model\ImageFactoryQuery;
use ImageFactory\Resolver\FactoryResolver;
use ImageFactory\Response\FactoryResponse;
use ImageFactory\Util\PathInfo;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Thelia\Model\ProductImage;
use Thelia\Tools\URL;

/**
 * Class FactoryHandler
 * @package ImageFactory\Handler
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class FactoryHandler
{
    const PHP_CACHE_KEY = 'ImageFactory';

    /** @var EventDispatcherInterface */
    protected $eventDispatcher;

    /** @var FactoryResolver */
    protected $factoryResolver;

    /** @var CacheProvider */
    protected $cacheProvider;

    /** @var URL */
    protected $URL;

    /** @var ContainerInterface */
    protected $container;

    /**
     * FactoryHandler constructor.
     * @param EventDispatcherInterface $eventDispatcher
     * @param URL $url
     * @param ContainerInterface $container
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, URL $url, ContainerInterface $container)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->URL = $url;
        $this->container = $container;
    }

    /**
     * @return FactoryResolver
     */
    public function getFactoryResolver()
    {
        if (null === $this->factoryResolver) {
            $this->factoryResolver = new FactoryResolver($this->getFactories());
        }

        return $this->factoryResolver;
    }

    /**
     * @return CacheProvider|FilesystemCache
     */
    protected function getCacheProvider()
    {
        if (null === $this->cacheProvider) {
            $env = $this->container->get('kernel')->getEnvironment();

            $this->cacheProvider = new FilesystemCache(
                THELIA_CACHE_DIR . $env . DS
            );
        }
        return $this->cacheProvider;
    }

    /**
     * @param Request $request
     * @return null|Response
     */
    public function getResponse(Request $request)
    {
        $path = $request->getPathInfo();
        /** @var FactoryResponse $factoryResponse */
        if (null !== $factoryResponse = $this->getFactoryResolver()->resolveByUrl($path)) {
            return new Response(
                null !== $factoryResponse->getImageBinary() ? $factoryResponse->getImageBinary() : file_get_contents($factoryResponse->getImageFullDestinationPath()),
                $factoryResponse->isImageNotFound() ? 404 : 200,
                [
                    'Content-Type' => 'image/' . $factoryResponse->getImageDestinationExtension()
                ]
            );
        }

        return null;
    }

    /**
     * @param string $factoryCode
     * @param ProductImage $imageModel
     * @return FactoryResponse
     */
    public function getUri($factoryCode, $imageModel)
    {
        $url = $this->getUrl($factoryCode, $imageModel);

        $parseUrl = parse_url($url);

        return $parseUrl['path'];
    }

    /**
     * @param string $factoryCode
     * @param ProductImage $imageModel
     * @param string $path
     * @return FactoryResponse
     */
    public function getUrl($factoryCode, $imageModel = null, $path = null)
    {
        if ($path !== null) {
            $pathInfo = new PathInfo($path);
        }

        if (!isset($pathInfo)) {
            if (!($imageModel instanceof ActiveRecordInterface) || !method_exists($imageModel, 'getFile')) {
                throw new \InvalidArgumentException('Invalid argument imageModel or pathinfo is require');
            }

            $pathInfo = new PathInfo($this->getPathByClassName($imageModel, $imageModel->getFile()));
        }

        $factory = $this->getFactoryResolver()->getByCode($factoryCode);
        $factory->setDisableProcessGenerate(true);
        $factoryResponse = $this->getFactoryResolver()->resolveByFactoryAndImagePathInfo($factory, $pathInfo);

        return $this->URL->absoluteUrl(
            '/' . $factoryResponse->getImageDestinationUri(),
            [],
            true
        );
    }

    /**
     * @since 0.3.0
     */
    public function reloadFactories()
    {
        $this->clearFactoriesInCache();
        $this->factoryResolver = null;
        $this->getFactoryResolver();
    }

    /**
     * @param string $class
     * @param string $fileName
     * @return string
     */
    protected function getPathByClassName($class, $fileName)
    {
        // remove namespace for just to have the class name
        $className = explode('\\', get_class($class));
        $className = end($className);

        // remove the word image "Image" , 'ProductImage ====> product
        $className = strtolower(substr($className, 0, -5));

        return THELIA_LOCAL_DIR . 'media' . DS . 'images' . DS . $className . DS . $fileName;
    }

    /**
     * @return FactoryEntityCollection
     */
    protected function getFactories()
    {
        if (null !== $factories = $this->getFactoriesInCache()) {
            return $factories;
        }

        $factories = $this->getPropelFactories();

        $this->setFactoriesInCache($factories);

        return $factories;
    }

    /**
     * @return null|FactoryEntityCollection
     */
    protected function getFactoriesInCache()
    {
        if (false !== $cache = $this->getCacheProvider()->fetch(self::PHP_CACHE_KEY)) {
            return unserialize($cache);
        }
        return null;
    }

    /**
     * @param FactoryEntityCollection $factories
     * @return bool
     */
    protected function setFactoriesInCache(FactoryEntityCollection $factories)
    {
        return $this->getCacheProvider()->save(self::PHP_CACHE_KEY, serialize($factories));
    }

    /**
     * @return bool
     */
    protected function clearFactoriesInCache()
    {
        return $this->getCacheProvider()->delete(self::PHP_CACHE_KEY);
    }

    /**
     * @return FactoryEntityCollection
     */
    protected function getPropelFactories()
    {
        $factories = new FactoryEntityCollection();

        $imageFactories = ImageFactoryQuery::create()->find();

        /** @var ImageFactoryModel $imageFactory */
        foreach ($imageFactories as $imageFactory) {
            $factory = new FactoryEntity();

            $factory
                ->setImagineLibraryCode($imageFactory->getImagineLibraryCode())
                ->setCode($imageFactory->getCode())
                ->setWidth($imageFactory->getWidth())
                ->setHeight($imageFactory->getHeight())
                ->setQuality($imageFactory->getQuality())
                ->setResizeMode($imageFactory->getResizeMode())
                ->setRotation($imageFactory->getRotation())
                ->setPrefix($imageFactory->getPrefix())
                ->setSuffix($imageFactory->getSuffix())
                ->setLayers($imageFactory->getLayers())
                ->setInterlace($imageFactory->getInterlace())
                ->setBaseDestinationPath(THELIA_WEB_DIR)
                ->setBaseSourcePath(THELIA_ROOT)
                ->setDestination($imageFactory->getDestination())
                ->setBackgroundColor($imageFactory->getBackgroundColor())
                ->setBackgroundOpacity($imageFactory->getBackgroundOpacity())
            ;

            $imageNotFoundSource = $imageFactory->getImageNotFoundSource();
            if (!empty($imageNotFoundSource)) {
                $factory->setImageNotFoundSourcePath($imageNotFoundSource);
            }

            $imageNotFoundDestinationFileName = $imageFactory->getImageNotFoundDestinationFileName();
            if (!empty($imageNotFoundDestinationFileName)) {
                $factory->setImageNotFoundDestinationFileName($imageNotFoundDestinationFileName);
            }

            $resamplingFilter = $imageFactory->getResamplingFilter();
            if (!empty($resamplingFilter)) {
                $factory->setResamplingFilter($resamplingFilter);
            }

            // Todo Add effects, filter, srcset, redirect, http source

            $sources = [];
            foreach ($imageFactory->getSources() as $source) {
                $sources[] = realpath($source) ? $source : THELIA_ROOT . $source;
            }
            $factory->setSources($sources);

            $factories->offsetSet($imageFactory->getCode(), $factory);
        }

        return $factories;
    }

    // Todo Faker
    /*
    public function generateImageFakeByFactory(FactoryEntity $factory)
    {
        return $this->factoryResolver->generateImageFakeByFactory($factory);
    }
    */
}
