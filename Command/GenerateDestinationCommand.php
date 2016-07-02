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

use ImageFactory\Entity\FactoryEntity;
use ImageFactory\Util\PathInfo;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Thelia\Command\ContainerAwareCommand;

/**
 * Class GenerateDestinationCommand
 * @package ImageFactory\Command
 * @author Gilles Bourgeat <gilles.bourgeat@gmail.com>
 */
class GenerateDestinationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setname('image-factory:generate-destination')
            ->addArgument(
                'codes',
                InputArgument::REQUIRED,
                'Codes of factories separated by comma.'
            )
            ->addOption(
                'force',
                null,
                null,
                'Force regeneration of images in the destination directories.'
            )
            ->setDescription('Generates all images in the destination directories of the factories.');
    }

    /**
     * @return \ImageFactory\Resolver\FactoryResolver
     */
    protected function getFactoryResolver()
    {
        return$this->getContainer()->get('image_factory.factory_handler')->getFactoryResolver();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $codes = $input->getArgument('codes');
        $codes = explode(',', $codes);

        foreach ($codes as $code) {
            $code = trim($code);
            $factory = $this->getFactoryResolver()->getByCode($code);

            $output->writeln('<info>Start of process for the image factory ' . $code . '</info>');

            $this->processGenerate($input, $output, $factory);

            $output->writeln('<info>End of process for the image factory ' . $code . '</info>');
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param FactoryEntity $factory
     */
    protected function processGenerate(InputInterface $input, OutputInterface $output, FactoryEntity $factory)
    {
        $finder = new Finder();
        foreach ($factory->getSources() as $source) {
            $path = $factory->getBaseSourcePath() . $source;

            $files = $finder->files()->in($path);

            $output->writeln('<info>' . $files->count() . ' images found in ' . $path . '</info>');

            $progress = new ProgressBar($output, $files->count());
            $progress->setFormat('debug');
            $progress->start();

            /** @var SplFileInfo $file */
            foreach ($files as $file) {
                if (in_array($file->getExtension(), FactoryEntity::$FILE_EXTENSION_DESTINATION)) {
                    $pathInfo = new PathInfo($file->getPathname());

                    if ((int) $input->getOption('force')) {
                        $factory->setForceRegeneration(true);
                    }

                    $this->getFactoryResolver()->resolveByFactoryAndImagePathInfo($factory, $pathInfo);

                    $progress->advance();
                }
            }

            $progress->finish();
            $output->writeln('');
        }
    }
}
