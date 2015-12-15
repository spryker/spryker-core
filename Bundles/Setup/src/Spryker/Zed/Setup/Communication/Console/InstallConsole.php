<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Spryker\Zed\Setup\Communication\SetupDependencyContainer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method SetupDependencyContainer getDependencyContainer()
 */
class InstallConsole extends Console
{

    const COMMAND_NAME = 'setup:install';
    const DESCRIPTION = 'Setup the application';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $setupInstallCommandNames = $this->getDependencyContainer()->createSetupInstallCommandNames();

        foreach ($setupInstallCommandNames as $key => $value) {
            if (is_array($value)) {
                $this->runDependingCommand($key, $value);
            } else {
                $this->runDependingCommand($value);
            }
        }
    }

}
