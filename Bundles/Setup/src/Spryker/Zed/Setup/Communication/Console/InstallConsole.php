<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Setup\Communication\SetupCommunicationFactory getFactory()
 */
class InstallConsole extends Console
{
    public const COMMAND_NAME = 'setup:install';
    public const DESCRIPTION = 'Setup the application';

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
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $setupInstallCommandNames = $this->getFactory()->getSetupInstallCommandNames();

        foreach ($setupInstallCommandNames as $key => $value) {
            if (is_array($value)) {
                $this->runDependingCommand($key, $value);
            } else {
                $this->runDependingCommand($value);
            }

            if ($this->hasError()) {
                return $this->getLastExitCode();
            }
        }

        return static::CODE_SUCCESS;
    }
}
