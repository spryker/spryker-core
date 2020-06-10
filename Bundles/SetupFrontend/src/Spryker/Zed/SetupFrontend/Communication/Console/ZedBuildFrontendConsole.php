<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Communication\Console;

use Generated\Shared\Transfer\SetupFrontendConfigurationTransfer;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\SetupFrontend\Business\SetupFrontendFacadeInterface getFacade()
 */
class ZedBuildFrontendConsole extends Console
{
    public const COMMAND_NAME = 'frontend:zed:build';
    public const DESCRIPTION = 'This command will build Zed\'s frontend.';

    public const OPTION_ENVIRONMENT = 'environment';
    public const OPTION_ENVIRONMENT_SHORT = 'e';
    public const OPTION_ENVIRONMENT_DESCRIPTION = 'Sets the environment to run the command. Currently available environments: production';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addOption(
            static::OPTION_ENVIRONMENT,
            static::OPTION_ENVIRONMENT_SHORT,
            InputOption::VALUE_REQUIRED,
            static::OPTION_ENVIRONMENT_DESCRIPTION
        );

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
        $this->info('Build Zed frontend');

        $setupFrontendConfigurationTransfer = new SetupFrontendConfigurationTransfer();
        if ($input->hasOption(static::OPTION_ENVIRONMENT)) {
            $setupFrontendConfigurationTransfer->setEnvironment($input->getOption(static::OPTION_ENVIRONMENT));
        }

        if ($this->getFacade()->buildZedFrontend($this->getMessenger(), $setupFrontendConfigurationTransfer)) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
