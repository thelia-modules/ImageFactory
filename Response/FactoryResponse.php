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

namespace ImageFactory\Response;

use ImageFactory\Entity\FactoryEntity;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;

/**
 * Class FactoryResponse
 * @package ImageFactory\Response
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class FactoryResponse
{
    /** @var string */
    protected $imageDestinationPath;

    /** @var string */
    protected $imageSourceFileName;

    /** @var string */
    protected $imageDestinationFileName;

    /** @var string */
    protected $imageSourceExtension;

    /** @var string */
    protected $imageDestinationExtension;

    /** @var string */
    protected $imageSourcePath;

    /** @var FactoryEntity */
    protected $factory;

    /** @var ImagineInterface */
    protected $imagine;

    /** @var ImageInterface */
    protected $image;

    /** @var bool */
    protected $imageProcessFinished = false;

    /** @var bool */
    protected $imageNotFound = false;

    /** @var string */
    protected $imageBinary;

    /** @var  */
    protected $html;

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     * @return FactoryResponse
     */
    public function setHtml($html)
    {
        $this->html = $html;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->html;
    }

    /**
     * @return string
     */
    public function getImageBinary()
    {
        return $this->imageBinary;
    }

    /**
     * @param string $imageBinary
     * @return FactoryResponse
     */
    public function setImageBinary($imageBinary)
    {
        $this->imageBinary = $imageBinary;
        return $this;
    }


    /**
     * @return ImageInterface
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param ImageInterface $image
     * @return FactoryResponse
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return ImagineInterface
     */
    public function getImagine()
    {
        return $this->imagine;
    }

    /**
     * @param ImagineInterface $imagine
     * @return FactoryResponse
     */
    public function setImagine($imagine)
    {
        $this->imagine = $imagine;
        return $this;
    }

    /**
     * @return FactoryEntity
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param FactoryEntity $factory
     * @return FactoryResponse
     */
    public function setFactory(FactoryEntity $factory)
    {
        $this->factory = $factory;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isImageNotFound()
    {
        return $this->imageNotFound;
    }

    /**
     * @param boolean $imageNotFound
     * @return FactoryResponse
     */
    public function setImageNotFound($imageNotFound)
    {
        $this->imageNotFound = $imageNotFound;
        return $this;
    }


    /**
     * @return boolean
     */
    public function isImageProcessFinished()
    {
        return $this->imageProcessFinished;
    }

    /**
     * @param boolean $imageProcessFinished
     * @return FactoryResponse
     */
    public function setImageProcessFinished($imageProcessFinished)
    {
        $this->imageProcessFinished = $imageProcessFinished;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageDestinationPath()
    {
        return $this->imageDestinationPath;
    }

    /**
     * @param string $imageDestinationPath
     * @return FactoryResponse
     */
    public function setImageDestinationPath($imageDestinationPath)
    {
        $this->imageDestinationPath = $imageDestinationPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageSourcePath()
    {
        return $this->imageSourcePath;
    }

    /**
     * @param string $imageSourcePath
     * @return FactoryResponse
     */
    public function setImageSourcePath($imageSourcePath)
    {
        $this->imageSourcePath = $imageSourcePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageSourceFileName()
    {
        return $this->imageSourceFileName;
    }

    /**
     * @param string $imageSourceFileName
     * @return FactoryResponse
     */
    public function setImageSourceFileName($imageSourceFileName)
    {
        $this->imageSourceFileName = $imageSourceFileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageDestinationFileName()
    {
        return $this->imageDestinationFileName;
    }

    /**
     * @param string $imageDestinationFileName
     * @return FactoryResponse
     */
    public function setImageDestinationFileName($imageDestinationFileName)
    {
        $this->imageDestinationFileName = $imageDestinationFileName;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageSourceExtension()
    {
        return $this->imageSourceExtension;
    }

    /**
     * @param string $imageSourceExtension
     * @return FactoryResponse
     */
    public function setImageSourceExtension($imageSourceExtension)
    {
        $this->imageSourceExtension = $imageSourceExtension;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageDestinationExtension()
    {
        return $this->imageDestinationExtension;
    }

    /**
     * @param string $imageDestinationExtension
     * @return FactoryResponse
     */
    public function setImageDestinationExtension($imageDestinationExtension)
    {
        $this->imageDestinationExtension = $imageDestinationExtension;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageFullSourcePath()
    {
        return $this->getImageSourcePath()
        . DS . $this->getImageSourceFileName()
        . '.' . $this->getImageSourceExtension();
    }

    /**
     * @return string
     */
    public function getImageFullDestinationPath()
    {
        return $this->getImageDestinationPath()
        . DS . $this->getImageDestinationFileName()
        . '.' . $this->getImageDestinationExtension();
    }

    /**
     * @return string uri
     */
    public function getImageDestinationUri()
    {
        return $this->getFactory()->getDestination()
        . '/'. $this->getImageDestinationFileName()
        . '.' . $this->getImageDestinationExtension();
    }
}
