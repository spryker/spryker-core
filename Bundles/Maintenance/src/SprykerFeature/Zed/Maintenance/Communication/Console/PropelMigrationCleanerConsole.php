<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */
namespace SprykerFeature\Zed\Maintenance\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use SprykerFeature\Zed\Setup\Communication\Console\InstallConsole;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method MaintenanceFacade getFacade()
 */
class PropelMigrationCleanerConsole extends Console
{
    const COMMAND_NAME = 'maintenance:rebuild-propel';
    const COMMAND_DESCRIPTION = 'Rebuild Propel models after database server switch';

    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESCRIPTION)
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Traversing Bundles for Propel models...');

        $facade = $this->getFacade();
        $facade->cleanPropelMigration();

        $this->info('Cleanup finished.');

        $this->runDependingCommand(InstallConsole::COMMAND_NAME);
    }
}
