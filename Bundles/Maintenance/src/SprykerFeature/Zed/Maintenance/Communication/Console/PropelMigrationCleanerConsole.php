<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method MaintenanceFacade getFacade()
 */
class PropelMigrationCleanerConsole extends Console
{

    const COMMAND_NAME = 'maintenance:clean-propel-base';
    const COMMAND_DESCRIPTION = 'Clean up Propel base files';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription(self::COMMAND_DESCRIPTION)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->info('Traversing Bundles...');

        $facade = $this->getFacade();
        $facade->cleanPropelMigration();

        $this->info('Cleanup finished.');
    }

}
