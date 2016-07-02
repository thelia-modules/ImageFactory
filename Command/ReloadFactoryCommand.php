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

namespace ImageFactory\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thelia\Command\ContainerAwareCommand;

/**
 * Class ReloadFactoryCommand
 * @package ImageFactory\Command
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class ReloadFactoryCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setname('image-factory:reload-factory')
            ->setDescription('Reloads the factories to the cache.');
    }

    /**
     * @return \ImageFactory\Handler\FactoryHandler
     */
    protected function getFactoryHandler()
    {
        return$this->getContainer()->get('image_factory.factory_handler');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getFactoryHandler()->reloadFactories();
        $output->writeln('<info>Reloading of the factories completed.</info>');
    }
}
