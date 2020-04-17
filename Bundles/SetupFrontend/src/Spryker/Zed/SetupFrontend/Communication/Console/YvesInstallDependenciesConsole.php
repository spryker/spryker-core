<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated All dependencies are now installed via {@see InstallProjectDependenciesConsole}
 *
 * @method \Spryker\Zed\SetupFrontend\Business\SetupFrontendFacadeInterface getFacade()
 */
class YvesInstallDependenciesConsole extends InstallProjectDependenciesConsole
{
    public const COMMAND_NAME = 'frontend:yves:install-dependencies';
    public const DESCRIPTION = 'This command will install Yves Module dependencies.';

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
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getMessenger()->notice('DEPRECATED: All dependencies are now installed via single command: ' . InstallProjectDependenciesConsole::COMMAND_NAME);

        return parent::execute($input, $output);
    }
}
