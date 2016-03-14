<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace ImageFactory;

use ImageFactory\Model\ImageFactoryQuery;
use Thelia\Install\Database;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Module\BaseModule;

/**
 * Class ImageFactory
 * @package ImageFactory
 * @author Gilles Bourgeat <gilles@thelia.net>
 */
class ImageFactory extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'imagefactory';

    /**
     * @param ConnectionInterface $con
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            ImageFactoryQuery::create()->findOne();
        } catch (\Exception $e) {
            (new Database($con))->insertSql(null, [__DIR__ . "/Config/thelia.sql", __DIR__ . "/Config/insert.sql"]);
        }
    }
}
