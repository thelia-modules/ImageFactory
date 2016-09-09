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

namespace ImageFactory\Util;

/**
 * Class PathInfo
 * @package ImageFactory\Util
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class PathInfo
{
    /** @var string */
    protected $dirname;

    /** @var string */
    protected $basename;

    /** @var string */
    protected $extension;

    /** @var string */
    protected $filename;

    public function __construct($path)
    {
        $pathInfo = pathinfo($path);

        if (isset($pathInfo['dirname'])) {
            $this->setDirname($pathInfo['dirname']);
        }

        if (isset($pathInfo['basename'])) {
            $this->setBasename($pathInfo['basename']);
        }

        if (isset($pathInfo['extension'])) {
            $this->setExtension($pathInfo['extension']);
        }

        if (isset($pathInfo['filename'])) {
            $this->setFilename($pathInfo['filename']);
        }
    }

    /**
     * @return string
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * @param string $dirname
     * @return PathInfo
     */
    public function setDirname($dirname)
    {
        $this->dirname = urldecode($dirname);
        return $this;
    }

    /**
     * @return string
     */
    public function getBasename()
    {
        return $this->basename;
    }

    /**
     * @param string $basename
     * @return PathInfo
     */
    public function setBasename($basename)
    {
        $this->basename = urldecode($basename);
        return $this;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @param string $extension
     * @return PathInfo
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     * @return PathInfo
     */
    public function setFilename($filename)
    {
        $this->filename = urldecode($filename);
        return $this;
    }
}
