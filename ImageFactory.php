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
            (new Database($con))->insertSql(null, [$this->getSetupDir() . "thelia.sql", $this->getSetupDir() . "insert.sql"]);
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
            ->in($this->getSetupDir() . 'update'. DS . 'sql')
        ;

        $database = new Database($con);

        /** @var SplFileInfo $updateSQLFile */
        foreach ($finder as $updateSQLFile) {
            if (version_compare($currentVersion, str_replace('.sql', '', $updateSQLFile->getFilename()), '<')) {
                $database->insertSql(null, [$updateSQLFile->getPathname()]);
            }
        }
    }

    /**
     * @return string
     * @since 0.2.4
     */
    protected function getSetupDir()
    {
        return __DIR__ . DS . 'setup' . DS;
    }
}
