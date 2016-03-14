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

/**
 * Class FactoryEntityCollection
 * @package ImageFactory\Entity
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class FactoryEntityCollection extends EntityCollection
{
    /**
     * @param int $offset
     * @param FactoryEntity $value
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof FactoryEntity)) {
            throw new \InvalidArgumentException('Invalid argument value');
        }

        parent::offsetSet($offset, $value);
    }
}
