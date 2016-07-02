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
namespace ImageFactory\Entity;

use ImageFactory\Util\Format;
use Imagine\Image\ImageInterface;

/**
 * Class FactoryEntity
 * @package ImageFactory\Entity
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class FactoryEntity implements FactoryEntityInterface
{
    /** imagine library */
    const IMAGINE_LIBRARY8_GD = 'gd';
    const IMAGINE_LIBRARY8_IMAGICK = 'imagick';
    const IMAGINE_LIBRARY8_GMAGICK = 'gmagick';

    /** Resize mode */
    const RESIZE_MODE_EXACT_RATIO_WITH_BORDERS = 'exact_ratio_with_borders';
    const RESIZE_MODE_EXACT_RATIO_WITH_CROP = 'exact_ratio_with_crop';
    const RESIZE_MODE_KEEP_IMAGE_RATIO = 'keep_image_ratio';

    /** Files support */
    public static $FILE_EXTENSION_DESTINATION = ['jpg', 'jpeg', 'png', 'gif'];

    /** @var string[] */
    protected $sources = [];

    /** @var string */
    protected $destination;

    /** @var string */
    protected $baseDestinationPath;

    /** @var string */
    protected $baseSourcePath;

    /** @var string default empty */
    protected $code = '';

    /** @var int default 100 */
    protected $width = 100;

    /** @var int default 100 */
    protected $height = 100;

    /** @var int default 75 */
    protected $quality = 75;

    /** @var string */
    protected $resizeMode = self::RESIZE_MODE_EXACT_RATIO_WITH_BORDERS;

    /** @var int default 0 */
    protected $rotation = 0;

    /** @var string default empty */
    protected $prefix = '';

    /** @var string default empty */
    protected $suffix = '';

    /** @var string[] */
    protected $layers = [];

    /** @var EffectEntityCollection */
    protected $effects = [];

    /** @var string[] */
    protected $pixelRatios = [];

    /** @var string default gd */
    protected $imagineLibraryCode = self::IMAGINE_LIBRARY8_GD;

    /** @var string */
    protected $imageNotFoundSourcePath;

    /** @var bool default true */
    protected $imageNotFoundActivate = true;

    /** @var string */
    protected $imageNotFoundDestinationFileName = 'image-not-found';

    /** @var bool */
    protected $debug = false;

    /** @var string|null */
    protected $backgroundColor;

    /** @var string|null */
    protected $backgroundOpacity;

    /** @var bool */
    protected $allowZoom = false;

    /** @var bool */
    protected $persist = true;

    /** @var string */
    protected $interlace = ImageInterface::INTERLACE_NONE;

    /** @var bool */
    protected $faker = false;

    /** @var bool */
    protected $disableProcessGenerate = false;

    /** @var bool */
    protected $forceRegeneration = false;

    /** @var string */
    protected $resamplingFilter = ImageInterface::FILTER_UNDEFINED;

    /**
     * @return string
     */
    public function getResamplingFilter()
    {
        return $this->resamplingFilter;
    }

    /**
     * @param string $resamplingFilter
     * @return FactoryEntity
     */
    public function setResamplingFilter($resamplingFilter)
    {
        if (!in_array($resamplingFilter, [
            ImageInterface::FILTER_UNDEFINED,
            ImageInterface::FILTER_POINT,
            ImageInterface::FILTER_BOX,
            ImageInterface::FILTER_TRIANGLE,
            ImageInterface::FILTER_HERMITE,
            ImageInterface::FILTER_HANNING,
            ImageInterface::FILTER_HAMMING,
            ImageInterface::FILTER_BLACKMAN,
            ImageInterface::FILTER_GAUSSIAN,
            ImageInterface::FILTER_QUADRATIC,
            ImageInterface::FILTER_CUBIC,
            ImageInterface::FILTER_CATROM,
            ImageInterface::FILTER_MITCHELL,
            ImageInterface::FILTER_LANCZOS,
            ImageInterface::FILTER_BESSEL,
            ImageInterface::FILTER_SINC
        ])) {
            throw new \InvalidArgumentException('Invalid argument resampling filter');
        }

        $this->resamplingFilter = $resamplingFilter;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isForceRegeneration()
    {
        return $this->forceRegeneration;
    }

    /**
     * @param boolean $forceRegeneration
     * @return FactoryEntity
     */
    public function setForceRegeneration($forceRegeneration)
    {
        $this->forceRegeneration = $forceRegeneration;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDisableProcessGenerate()
    {
        return $this->disableProcessGenerate;
    }

    /**
     * @param boolean $disableProcessGenerate
     * @return FactoryEntity
     */
    public function setDisableProcessGenerate($disableProcessGenerate)
    {
        $this->disableProcessGenerate = $disableProcessGenerate;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isFaker()
    {
        return $this->faker;
    }

    /**
     * @param boolean $faker
     * @return FactoryEntity
     */
    public function setFaker($faker)
    {
        $this->faker = $faker;
        return $this;
    }

    /**
     * @return string
     */
    public function getInterlace()
    {
        return $this->interlace;
    }

    /**
     * @param string $interlace
     * @return FactoryEntity
     */
    public function setInterlace($interlace)
    {
        if (!in_array($interlace, [
            ImageInterface::INTERLACE_NONE,
            ImageInterface::INTERLACE_LINE,
            ImageInterface::INTERLACE_PARTITION,
            ImageInterface::INTERLACE_PLANE
        ])) {
            throw new \InvalidArgumentException('Invalid argument interlace');
        }
        $this->interlace = $interlace;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPersist()
    {
        return $this->persist;
    }

    /**
     * @param boolean $persist
     * @return FactoryEntity
     */
    public function setPersist($persist)
    {
        $this->persist = (bool) $persist;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getLayers()
    {
        return $this->layers;
    }

    /**
     * @param \string[] $layers
     * @return FactoryEntity
     */
    public function setLayers($layers)
    {
        $this->layers = $layers;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageNotFoundSourcePath()
    {
        return $this->imageNotFoundSourcePath !== null
            ? $this->imageNotFoundSourcePath : __DIR__ . DS . '..' . DS . 'images' . DS . 'not-found.jpg';
    }

    /**
     * @param string $imageNotFoundSourcePath
     * @return FactoryEntity
     */
    public function setImageNotFoundSourcePath($imageNotFoundSourcePath)
    {
        $this->imageNotFoundSourcePath = Format::normalizePath((string) $imageNotFoundSourcePath);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isAllowZoom()
    {
        return $this->allowZoom;
    }

    /**
     * If true, you allowed to resize an image to match the required width and height,
     * causing, in most cases, a quality loss.
     * If false, the image will never be zoomed. Default is false.
     *
     * @param boolean $allowZoom
     * @return FactoryEntity
     */
    public function setAllowZoom($allowZoom)
    {
        $this->allowZoom = (bool) $allowZoom;
        return $this;
    }

    /**
     * The color applied to empty image parts during processing. Use rgb or rrggbb color format
     * Default FFFFFF
     *
     * @return null|string
     */
    public function getBackgroundColor()
    {
        return $this->backgroundColor;
    }

    /**
     * @param null|string $backgroundColor
     * @return FactoryEntity
     */
    public function setBackgroundColor($backgroundColor)
    {
        $backgroundColor = (string) $backgroundColor;

        if (!preg_match('/^([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $backgroundColor)) {
            throw new \InvalidArgumentException('The argument "backgroundColor" is not valid');
        }

        $this->backgroundColor = $backgroundColor;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getBackgroundOpacity()
    {
        return $this->backgroundOpacity;
    }

    /**
     * @param null|string $backgroundOpacity
     * @return FactoryEntity
     */
    public function setBackgroundOpacity($backgroundOpacity)
    {
        $backgroundOpacity = (int) $backgroundOpacity;

        if ($backgroundOpacity < 0 || $backgroundOpacity > 100) {
            throw new \InvalidArgumentException('The argument "backgroundOpacity" is not valid');
        }

        $this->backgroundOpacity = $backgroundOpacity;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * @param boolean $debug
     * @return FactoryEntity
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageNotFoundDestinationFileName()
    {
        return $this->imageNotFoundDestinationFileName;
    }

    /**
     * @param string $imageNotFoundDestinationFileName
     * @return FactoryEntity
     */
    public function setImageNotFoundDestinationFileName($imageNotFoundDestinationFileName)
    {
        $this->imageNotFoundDestinationFileName = (string) $imageNotFoundDestinationFileName;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isImageNotFoundActivate()
    {
        return $this->imageNotFoundActivate;
    }

    /**
     * @param boolean $imageNotFoundActivate
     * @return FactoryEntity
     */
    public function setImageNotFoundActivate($imageNotFoundActivate)
    {
        $this->imageNotFoundActivate = (bool) $imageNotFoundActivate;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * @param int|string $key
     * @return string
     */
    public function getSource($key)
    {
        return $this->sources[$key];
    }

    /**
     * @param \string[] $sources
     * @return FactoryEntity
     */
    public function setSources($sources)
    {
        foreach ($sources as $key => $path) {
            $this->addSource($key, $path);
        }

        return $this;
    }

    /**
     * @param string|int $key
     * @param string $path
     * @return $this
     */
    public function addSource($key, $path)
    {
        if (empty($path)) {
            throw new \InvalidArgumentException('The argument "path" is not valid');
        }

        $this->sources[$key] = Format::normalizePath($path);
        return $this;
    }

    /**
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param string $destination
     * @return FactoryEntity
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return FactoryEntity
     */
    public function setCode($code)
    {
        $this->code = (string) $code;
        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return FactoryEntity
     */
    public function setWidth($width)
    {
        if ((int) $width < 1) {
            throw new \InvalidArgumentException('Invalid argument width');
        }
        $this->width = (int) $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return FactoryEntity
     */
    public function setHeight($height)
    {
        if ((int) $height < 1) {
            throw new \InvalidArgumentException('Invalid argument height');
        }
        $this->height = (int) $height;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * The generated image quality, from 1 to 100. The default value is 75
     *
     * @param int $quality
     * @return FactoryEntity
     */
    public function setQuality($quality)
    {
        if ((int) $quality < 1 || (int) $quality > 100) {
            throw new \InvalidArgumentException('Invalid argument quality');
        }
        $this->quality = (int) $quality;
        return $this;
    }

    /**
     * @return string
     */
    public function getResizeMode()
    {
        return $this->resizeMode;
    }

    /**
     * @param string $resizeMode
     * @return FactoryEntity
     */
    public function setResizeMode($resizeMode)
    {
        if (!in_array($resizeMode, [
            self::RESIZE_MODE_EXACT_RATIO_WITH_BORDERS,
            self::RESIZE_MODE_EXACT_RATIO_WITH_CROP,
            self::RESIZE_MODE_KEEP_IMAGE_RATIO
        ])) {
            throw new \InvalidArgumentException('Invalid argument resizeMode');
        }
        $this->resizeMode = (string) $resizeMode;
        return $this;
    }

    /**
     * @return int
     */
    public function getRotation()
    {
        return $this->rotation;
    }

    /**
     * @param int $rotation
     * @return FactoryEntity
     */
    public function setRotation($rotation)
    {
        $this->rotation = (int) $rotation;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return FactoryEntity
     */
    public function setPrefix($prefix)
    {
        $this->prefix = (string) $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     * @return FactoryEntity
     */
    public function setSuffix($suffix)
    {
        $this->suffix = (string) $suffix;
        return $this;
    }

    /**
     * @return EffectEntityCollection
     */
    public function getEffects()
    {
        return $this->effects;
    }

    /**
     * @param EffectEntityCollection $effects
     * @return FactoryEntity
     */
    public function setEffects(EffectEntityCollection $effects)
    {
        $this->effects = $effects;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getPixelRatios()
    {
        return $this->pixelRatios;
    }

    /**
     * @param \string[] $pixelRatios
     * @return FactoryEntity
     */
    public function setPixelRatios(array $pixelRatios)
    {
        $this->pixelRatios = $pixelRatios;
        return $this;
    }

    /**
     * @return string
     */
    public function getImagineLibraryCode()
    {
        return $this->imagineLibraryCode;
    }

    /**
     * @param string $imagineLibraryCode
     * @return FactoryEntity
     */
    public function setImagineLibraryCode($imagineLibraryCode)
    {
        if (!in_array($imagineLibraryCode, [
            self::IMAGINE_LIBRARY8_GD,
            self::IMAGINE_LIBRARY8_IMAGICK,
            self::IMAGINE_LIBRARY8_GMAGICK
        ])) {
            throw new \InvalidArgumentException('Invalid argument library code');
        }

        $this->imagineLibraryCode = $imagineLibraryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseDestinationPath()
    {
        return $this->baseDestinationPath;
    }

    /**
     * @param string $baseDestinationPath
     * @return FactoryEntity
     */
    public function setBaseDestinationPath($baseDestinationPath)
    {
        $this->baseDestinationPath = Format::normalizePath($baseDestinationPath);
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseSourcePath()
    {
        return $this->baseSourcePath;
    }

    /**
     * @param string $baseSourcePath
     * @return FactoryEntity
     */
    public function setBaseSourcePath($baseSourcePath)
    {
        $this->baseSourcePath = $baseSourcePath;
        return $this;
    }
}
