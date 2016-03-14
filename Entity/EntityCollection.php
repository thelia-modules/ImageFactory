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

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Class EntityCollection
 * @package ImageFactory\Entity
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
abstract class EntityCollection implements ArrayAccess, Iterator, Countable
{
    /** @var Object[] $entities */
    private $entities = [];

    /**
     * @return int
     */
    public function count()
    {
        return count($this->entities);
    }

    /**
     * @param int $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->entities[] = $value;
        } else {
            $this->entities[$offset] = $value;
        }
    }

    /**
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->entities[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->entities[$offset]);
    }

    /**
     * @param int $offset
     * @return null|mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->entities[$offset]) ? $this->entities[$offset] : null;
    }

    public function rewind()
    {
        reset($this->entities);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->entities);
    }

    /**
     * @return string
     */
    public function key()
    {
        return key($this->entities);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->entities);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        $key = key($this->entities);
        return ($key !== null && $key !== false);
    }
}
