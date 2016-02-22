<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Installer\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Installer\Business\InstallerFacade getFacade()
 */
class InitializeDatabaseConsole extends Console
{

    const COMMAND_NAME = 'setup:init-db';
    const DESCRIPTION = 'Fill the database with required data';
    const EXIT_CODE_ERROR = 1;
    const EXIT_CODE_SUCCESS = 0;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME)
            ->setDescription(self::DESCRIPTION);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return null|int null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $installerPlugins = $this->getFacade()->getInstallers();

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
