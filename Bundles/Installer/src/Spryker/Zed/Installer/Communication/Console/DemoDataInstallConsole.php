<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Installer\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Spryker\Zed\Installer\Business\InstallerFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method InstallerFacade getFacade()
 */
class DemoDataInstallConsole extends Console
{

    const COMMAND_NAME = 'setup:install-demo-data';
    const DESCRIPTION = 'Install demo data';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installerPlugins = $this->getFacade()->getDemoDataInstallers();

        $messenger = $this->getMessenger();

        foreach ($installerPlugins as $installer) {
            $installer->setMessenger($messenger);
            $output->writeln(date('c') . ' Next importer ' . get_class($installer));
            $installer->install();
            $output->writeln('Done ' . get_class($installer));
        }
    }

}
