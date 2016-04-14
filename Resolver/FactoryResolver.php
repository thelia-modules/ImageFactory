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

namespace ImageFactory\Resolver;

use ImageFactory\Entity\EffectEntity;
use ImageFactory\Entity\FactoryEntity;
use ImageFactory\Entity\FactoryEntityCollection;
use ImageFactory\Exception\ImageNotFoundException;
use ImageFactory\Response\FactoryResponse;
use ImageFactory\Util\PathInfo;
use Imagine\Image\AbstractImagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Imagine\Image\Palette\Color\ColorInterface;
use Imagine\Image\Palette\RGB;
use Imagine\Image\Point;
use Imagine\Gd\Imagine as ImagineGd;
use Imagine\Imagick\Imagine as ImagineImagick;
use Imagine\Gmagick\Imagine as ImagineGmagick;

/**
 * Class FactoryResolver
 * @package ImageFactory\Resolver
 * @author Gilles Bourgeat <gilles@thelia.net>
 * @author Franck Allimant <franck@cqfdev.fr>
 */
class FactoryResolver
{
    /** @var FactoryEntityCollection */
    protected $factoryCollection;

    /**
     * FactoryResolver constructor.
     * @param FactoryEntityCollection $factoryCollection
     */
    public function __construct(FactoryEntityCollection $factoryCollection)
    {
        $this->factoryCollection = $factoryCollection;
    }

    /**
     * @param string $code
     * @return FactoryEntity
     */
    public function getByCode($code)
    {
        if (isset($this->factoryCollection[$code])) {
            return $this->factoryCollection[$code];
        }

        throw new \InvalidArgumentException('Invalid argument code, factory "' . $code . '" not found');
    }

    /**
     * @param FactoryEntity $factory
     * @param PathInfo $pathInfo
     * @return FactoryResponse
     */
    public function resolveByFactoryAndImagePathInfo(FactoryEntity $factory, PathInfo $pathInfo)
    {
        $factoryResponse = (new FactoryResponse())
            ->setFactory($factory)
            ->setImageDestinationFileName(
                $factory->getPrefix()
                . $pathInfo->getFilename()
                . $factory->getSuffix()
            )
            ->setImageSourceFileName($pathInfo->getFilename())
            ->setImageDestinationExtension($pathInfo->getExtension())
            ->setImageSourceExtension($pathInfo->getExtension())
            ->setImageSourcePath($pathInfo->getDirname())
            ->setImageDestinationPath($this->getImageDestinationPath($factory));

        try {
            $this->imageProcess($factory, $factoryResponse);
        } catch (ImageNotFoundException $e) {
            return $this->generateImageNotFound($factory, $pathInfo, $factoryResponse);
        }

        return $factoryResponse;
    }

    /**
     * @param string $path
     * @return FactoryResponse|null
     */
    public function resolveByUrl($path)
    {
        $pathInfo = new PathInfo($path);

        $basename = $pathInfo->getBasename();

        // test if extension is an image
        if (null === $pathInfo->getExtension()
            || empty($basename)
            || !in_array(strtolower($pathInfo->getExtension()), FactoryEntity::$FILE_EXTENSION_DESTINATION)
        ) {
            return null;
        }

        // search factory
        if (null === $factory = $this->getFactoryByPathInfo($pathInfo)) {
            return null;
        }

        // init response
        $factoryResponse = new FactoryResponse();

        try {
            $this->initFactoryResponse($factory, $pathInfo, $factoryResponse);

            $this->initImageSourceInfo($factory, $pathInfo, $factoryResponse);

            $this->imageProcess($factory, $factoryResponse);
        } catch (ImageNotFoundException $e) {
            return $this->generateImageNotFound($factory, $pathInfo, $factoryResponse);
        }

        return $factoryResponse;
    }

    /**
     * @param FactoryEntity $factory
     * @param PathInfo $pathInfo
     * @param FactoryResponse $factoryResponse
     * @return FactoryResponse|null
     */
    protected function generateImageNotFound(FactoryEntity $factory, PathInfo $pathInfo, FactoryResponse $factoryResponse)
    {
        if ($factory->isImageNotFoundActivate()) {
            $this->constructFactoryResponseImageNotFound($factory, $pathInfo, $factoryResponse);

            $this->imageProcess($factory, $factoryResponse);
            return $factoryResponse;
        }
        return null;
    }

    /**
     * @param $pathInfo
     * @return FactoryEntity|null
     */
    protected function getFactoryByPathInfo(PathInfo $pathInfo)
    {
        /** @var FactoryEntity $factory */
        foreach ($this->factoryCollection as $factory) {
            if ($pathInfo->getDirname() === '/' . $factory->getDestination()) {
                return $factory;
            }
        }
        return null;
    }

    /**
     * @param FactoryEntity $factory
     * @param PathInfo $pathInfo
     * @param FactoryResponse $factoryResponse
     * @throws ImageNotFoundException
     */
    protected function initImageSourceInfo(
        FactoryEntity $factory,
        PathInfo $pathInfo,
        FactoryResponse $factoryResponse
    ) {
        $find = false;
        foreach ($factory->getSources() as $source) {
            // Todo srcset

            $regex = $this->getRegexSearch($factory, $pathInfo);

            if (preg_match($regex, $pathInfo->getBasename(), $match)) {
                if (file_exists($source . DS . $match['FileName'] . '.' . $pathInfo->getExtension())) {
                    $factoryResponse
                        ->setImageSourcePath($source)
                        ->setImageSourceFileName($match['FileName'])
                        ->setImageSourceExtension($pathInfo->getExtension());

                    $find = true;

                    break;
                }
            }
        }

        if (!$find) {
            $factoryResponse->setImageNotFound(true);
            throw new ImageNotFoundException();
        }
    }

    protected function getRegexSearch(FactoryEntity $factory, PathInfo $pathInfo)
    {
        return '/^'
            . $factory->getPrefix()
            . '(?P<FileName>.+)'
            . $factory->getSuffix()
            . '\.'
            . $pathInfo->getExtension()
            . '$/'
        ;
    }

    /**
     * @param FactoryEntity $factory
     * @return string
     */
    public function getImageDestinationPath(FactoryEntity $factory)
    {
        if (!$this->isAbsolutePath($factory->getDestination())) {
            return $factory->getBaseDestinationPath()
            . (substr($factory->getBaseDestinationPath(), -1) !== DS ?: DS)
            . $factory->getDestination();
        }
        return $factory->getDestination();
    }

    /**
     * @param FactoryEntity $factory
     * @param FactoryResponse $factoryResponse
     * @throws ImageNotFoundException
     */
    protected function imageProcess(FactoryEntity $factory, FactoryResponse $factoryResponse)
    {
        if ($factory->isDisableProcessGenerate()) {
            return;
        }

        // ignore process if image exist
        if (!file_exists($factoryResponse->getImageFullDestinationPath())) {
            $imagine = $this->getLibrary($factory->getImagineLibraryCode());

            $factoryResponse->setImagine($imagine);

            if (!$factoryResponse->isImageNotFound() && !file_exists($factoryResponse->getImageFullSourcePath())) {
                throw new ImageNotFoundException(
                    'Image source not found : '
                    . $factoryResponse->getImageFullSourcePath()
                );
            }

            $image = $imagine->open($factoryResponse->getImageFullSourcePath());

            $factoryResponse->setImage($image);

            // create folder if not exist
            if (!file_exists($factoryResponse->getImageDestinationPath())) {
                mkdir($factoryResponse->getImageDestinationPath(), 0777, true);
            }

            $backgroundColor = $this->getBackgroundColor($factory);


            $this->applyRotation($imagine, $image, $factory, $backgroundColor);

            $this->applyResize($imagine, $image, $factory, $backgroundColor);

            $this->applyEffect($imagine, $image, $factory, $backgroundColor);

            $this->applyLayer($imagine, $image, $factory, $backgroundColor);

            $this->applyInterlace($imagine, $image, $factory, $backgroundColor);

            // Todo metadata
            //$this->applyMetadata($imagine, $image, $factory, $backgroundColor);

            $factoryResponse->setImageBinary($image->get(
                $factoryResponse->getImageDestinationExtension(),
                [
                    'quality' => $factory->getQuality()
                ]
            ));

            if ($factory->isPersist()) {
                $image->save(
                    $factoryResponse->getImageFullDestinationPath(),
                    [
                        'quality' => $factory->getQuality()
                    ]
                );
            }
        }

        $factoryResponse->setImageProcessFinished(true);
    }

    /**
     * @param FactoryEntity $factory
     * @return ColorInterface<
     */
    protected function getBackgroundColor(FactoryEntity $factory)
    {
        $palette = new RGB();

        if ($factory->getBackgroundColor() !== null) {
            return $palette->color($factory->getBackgroundColor());
        }

        // Todo getBackgroundAlpha
        // Define a fully transparent white background color
        return $palette->color('FFFFFF', 100);
    }

    /**
     * @param ImagineInterface $imagine
     * @param ImageInterface $image
     * @param FactoryEntity $factory
     * @param ColorInterface $color
     */
    protected function applyInterlace(
        ImagineInterface $imagine,
        ImageInterface $image,
        FactoryEntity $factory,
        ColorInterface $color
    ) {
        $image->interlace($factory->getInterlace());
    }

    /**
     * @param ImagineInterface $imagine
     * @param ImageInterface $image
     * @param FactoryEntity $factory
     * @param ColorInterface $color
     */
    protected function applyLayer(
        ImagineInterface $imagine,
        ImageInterface $image,
        FactoryEntity $factory,
        ColorInterface $color
    ) {
        foreach ($factory->getLayers() as $key => $layer) {
            if (!$this->isAbsolutePath($layer)) {
                $layer = $factory->getBaseSourcePath()
                . (substr($factory->getBaseSourcePath(), -1) !== DS ?: DS)
                . $layer;
            }

            if (!file_exists($layer)) {
                throw new \InvalidArgumentException('Layer ' . $layer . 'not found');
            }

            $image->layers()->set($key, $imagine->open($layer));
        }
    }

    /**
     * @param ImagineInterface $imagine
     * @param ImageInterface $image
     * @param FactoryEntity $factory
     * @param ColorInterface $color
     */
    protected function applyEffect(
        ImagineInterface $imagine,
        ImageInterface $image,
        FactoryEntity $factory,
        ColorInterface $color
    ) {
        /** @var EffectEntity $effect */
        foreach ($factory->getEffects() as $name => $effect) {
            call_user_func_array(
                [$image->effects(), $effect->getMethodName()],
                $effect->getParams()
            );
        }
    }

    /**
     * @param ImagineInterface $imagine
     * @param ImageInterface $image
     * @param FactoryEntity $factory
     * @param ColorInterface $color
     */
    protected function applyRotation(
        ImagineInterface $imagine,
        ImageInterface $image,
        FactoryEntity $factory,
        ColorInterface $color
    ) {
        if ($factory->getRotation() !== 0) {
            $image->rotate($factory->getRotation(), $color);
        }
    }

    /**
     * @param ImagineInterface $imagine
     * @param ImageInterface $image
     * @param FactoryEntity $factory
     * @param ColorInterface $color
     */
    protected function applyResize(
        ImagineInterface $imagine,
        ImageInterface &$image,
        FactoryEntity $factory,
        ColorInterface $color
    ) {
        if (! (is_null($factory->getWidth()) && is_null($factory->getHeight()))) {
            $widthOrigin = $image->getSize()->getWidth();
            $heightOrigin = $image->getSize()->getHeight();

            $ratio = $widthOrigin / $heightOrigin;

            if (is_null($factory->getWidth())) {
                $factory->setWidth($factory->getHeight() * $ratio);
            }

            if (is_null($factory->getHeight())) {
                $factory->setHeight($factory->getWidth() / $ratio);
            }

            if (is_null($factory->getResizeMode())) {
                $factory->setResizeMode(FactoryEntity::RESIZE_MODE_KEEP_IMAGE_RATIO);
            }

            $widthDiff = $factory->getWidth() / $widthOrigin;
            $heightDiff = $factory->getHeight() / $heightOrigin;

            $deltaX = $deltaY = $borderWidth = $borderHeight = 0;

            if ($widthDiff > 1 && $heightDiff > 1) {
                $resizeWidth = $widthOrigin;
                $resizeHeight = $heightOrigin;

                // When cropping, be sure to always generate an image which is
                //  no smaller than the required size, zooming it if required.
                if ($factory->getResizeMode() == FactoryEntity::RESIZE_MODE_EXACT_RATIO_WITH_CROP) {
                    if ($factory->isAllowZoom()) {
                        if ($widthDiff > $heightDiff) {
                            $resizeWidth = $factory->getWidth();
                            $resizeHeight = intval($heightOrigin * $factory->getWidth() / $widthOrigin);
                            $deltaY = ($resizeHeight - $factory->getHeight()) / 2;
                        } else {
                            $resizeHeight = $factory->getHeight();
                            $resizeWidth = intval(($widthOrigin * $resizeHeight) / $heightOrigin);
                            $deltaX = ($resizeWidth - $factory->getWidth()) / 2;
                        }
                    } else {
                        // No zoom : final image may be smaller than the required size.
                        $factory->setWidth($resizeWidth);
                        $factory->setHeight($resizeHeight);
                    }
                }
            } elseif ($widthDiff > $heightDiff) {
                // Image height > image width
                $resizeHeight = $factory->getHeight();
                $resizeWidth = intval(($widthOrigin * $resizeHeight) / $heightOrigin);

                if ($factory->getResizeMode() == FactoryEntity::RESIZE_MODE_EXACT_RATIO_WITH_CROP) {
                    $resizeWidth = $factory->getWidth();
                    $resizeHeight = intval($heightOrigin * $factory->getWidth() / $widthOrigin);
                    $deltaY = ($resizeHeight - $factory->getHeight()) / 2;
                } elseif ($factory->getResizeMode() != FactoryEntity::RESIZE_MODE_EXACT_RATIO_WITH_BORDERS) {
                    $factory->setWidth($resizeWidth);
                }
            } else {
                // Image width > image height
                $resizeWidth = $factory->getWidth();
                $resizeHeight = intval($heightOrigin * $factory->getWidth() / $widthOrigin);

                if ($factory->getResizeMode() == FactoryEntity::RESIZE_MODE_EXACT_RATIO_WITH_CROP) {
                    $resizeHeight = $factory->getHeight();
                    $resizeWidth  = intval(($widthOrigin * $resizeHeight) / $heightOrigin);
                    $deltaX = ($resizeWidth - $factory->getWidth()) / 2;
                } elseif ($factory->getResizeMode() != FactoryEntity::RESIZE_MODE_EXACT_RATIO_WITH_BORDERS) {
                    $factory->setHeight($resizeHeight);
                }
            }

            $image->resize(new Box($resizeWidth, $resizeHeight));

            if ($factory->getResizeMode() == FactoryEntity::RESIZE_MODE_EXACT_RATIO_WITH_BORDERS) {
                $borderWidth = intval(($factory->getWidth() - $resizeWidth) / 2);
                $borderHeight = intval(($factory->getHeight() - $resizeHeight) / 2);

                $canvas = new Box($factory->getWidth(), $factory->getHeight());

                $image = $imagine->create($canvas, $color)
                    ->paste($image, new Point($borderWidth, $borderHeight));
            } elseif ($factory->getResizeMode() == FactoryEntity::RESIZE_MODE_EXACT_RATIO_WITH_CROP) {
                $image->crop(
                    new Point($deltaX, $deltaY),
                    new Box($factory->getWidth(), $factory->getHeight())
                );
            }
        }
    }

    /**
     * @param FactoryEntity $factory
     * @param PathInfo $pathInfo
     * @param FactoryResponse $factoryResponse
     */
    protected function initFactoryResponse(
        FactoryEntity $factory,
        PathInfo $pathInfo,
        FactoryResponse $factoryResponse
    ) {
        $factoryResponse
            ->setFactory($factory)
            ->setImageDestinationFileName($pathInfo->getFilename())
            ->setImageDestinationExtension($pathInfo->getExtension())
            ->setImageDestinationPath($this->getImageDestinationPath($factory));
    }

    /**
     * @param FactoryEntity $factory
     * @param PathInfo $pathInfo
     * @param FactoryResponse $factoryResponse
     */
    protected function constructFactoryResponseImageNotFound(
        FactoryEntity $factory,
        PathInfo $pathInfo,
        FactoryResponse $factoryResponse
    ) {
        $factoryResponse->setImageNotFound(true);

        $infoImageNotFound = new PathInfo($factory->getImageNotFoundFullSourcePath());

        if (!$this->isAbsolutePath($infoImageNotFound->getDirname())) {
            $factoryResponse->setImageSourcePath($factory->getBaseSourcePath()
                . (substr($factory->getBaseSourcePath(), -1) !== DS ?: DS)
                . $infoImageNotFound->getDirname());
        } else {
            $factoryResponse->setImageSourcePath($infoImageNotFound->getDirname());
        }

        $factoryResponse
            ->setImageDestinationFileName($factory->getImageNotFoundFileName())
            ->setImageSourceFileName($infoImageNotFound->getFilename())
            ->setImageSourceExtension($infoImageNotFound->getExtension());
    }

    /**
     * @param string $libraryCode
     * @return AbstractImagine
     */
    protected function getLibrary($libraryCode)
    {
        switch ($libraryCode) {
            case FactoryEntity::IMAGINE_LIBRARY8_GD:
                return new ImagineGd();
                break;
            case FactoryEntity::IMAGINE_LIBRARY8_IMAGICK:
                return new ImagineImagick();
                break;
            case FactoryEntity::IMAGINE_LIBRARY8_GMAGICK:
                return new ImagineGmagick();
                break;
        }

        throw new \InvalidArgumentException('Invalid library code');
    }

    /**
     * @param string $path
     * @return bool
     */
    protected function isAbsolutePath($path)
    {
        if (preg_match('#^[a-zA-Z]:|^\/#', $path)) {
            return true;
        }
        return false;
    }
}
