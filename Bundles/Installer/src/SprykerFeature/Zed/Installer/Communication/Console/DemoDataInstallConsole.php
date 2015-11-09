<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Installer\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Installer\Business\InstallerFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method InstallerFacade getFacade()
 */
class DemoDataInstallConsole extends Console
{

    const COMMAND_NAME = 'setup:install-demo-data';
    const DESCRIPTION = 'Install demo data';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installerPlugins = $this->getFacade()->getDemoDataInstaller();

        $messenger = $this->getMessenger();

        foreach ($installerPlugins as $installer) {
            $installer->setMessenger($messenger);
            $output->writeln(date('c') . ' Next importer ' . get_class($installer));
            $installer->install();
            $output->writeln('Done ' . get_class($installer));
        }
    }

}
