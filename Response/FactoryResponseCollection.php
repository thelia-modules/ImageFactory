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

use ImageFactory\Entity\EntityCollection;

/**
 * Class FactoryEntityCollection
 * @package ImageFactory\Entity
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class FactoryResponseCollection extends EntityCollection
{
    /**
     * @param int $offset
     * @param FactoryResponse $value
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof FactoryResponse)) {
            throw new \InvalidArgumentException('Invalid argument value');
        }

        parent::offsetSet($offset, $value);
    }
}
