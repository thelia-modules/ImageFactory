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

namespace ImageFactory;

use ImageFactory\Model\ImageFactoryQuery;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
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
     * @inheritDoc
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            ImageFactoryQuery::create()->findOne();
        } catch (\Exception $e) {
            (new Database($con))->insertSql(null, [__DIR__ . "/setup/thelia.sql", __DIR__ . "/setup/insert.sql"]);
        }
    }

    /**
     * @inheritDoc
     */
    public function update($currentVersion, $newVersion, ConnectionInterface $con = null)
    {
        $finder = (new Finder())
            ->files()
            ->name('#.*?\.sql#')
            ->in(__DIR__ . DS . 'Setup' . DS . 'update'. DS . 'sql')
        ;

        $database = new Database($con);

        /** @var SplFileInfo $updateSQLFile */
        foreach ($finder as $updateSQLFile) {
            if (version_compare($currentVersion, str_replace('.sql', '', $updateSQLFile->getFilename()), '<')) {
                $database->insertSql(null, [$updateSQLFile->getPathname()]);
            }
        }
    }
}
