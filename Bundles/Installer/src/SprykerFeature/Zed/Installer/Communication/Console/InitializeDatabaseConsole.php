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
class InitializeDatabaseConsole extends Console
{

    const COMMAND_NAME = 'setup:init-db';
    const DESCRIPTION = 'Fill the database with required data';
    const EXIT_CODE_ERROR = 1;
    const EXIT_CODE_SUCCESS = 0;

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
        $installerPlugins = $this->getFacade()->getInstaller();

        $messenger = $this->getMessenger();

        try {
            foreach ($installerPlugins as $installer) {
                $installer->setMessenger($messenger);
                $installer->install();
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return self::EXIT_CODE_ERROR;
        }

        return self::EXIT_CODE_SUCCESS;
    }

}
