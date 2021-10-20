<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated In next major all dependencies will be installed via {@see InstallProjectDependenciesConsole}
 *
 * @method \Spryker\Zed\SetupFrontend\Business\SetupFrontendFacadeInterface getFacade()
 */
class MerchantPortalInstallDependenciesConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'frontend:mp:install-dependencies';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will install Merchant Portal dependencies.';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->info('Install Merchant Portal dependencies');
        $this->getMessenger()->notice('DEPRECATED: In next major all dependencies will be installed via single command: ' . InstallProjectDependenciesConsole::COMMAND_NAME);

        if ($this->getFacade()->installMerchantPortalDependencies($this->getMessenger())) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
