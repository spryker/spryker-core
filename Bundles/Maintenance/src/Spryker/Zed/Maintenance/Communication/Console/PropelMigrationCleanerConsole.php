<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Maintenance\Business\MaintenanceFacade getFacade()
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
            ->setDescription(self::COMMAND_DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
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
