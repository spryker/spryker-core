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
class YvesBuildFrontendConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'frontend:yves:build';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will build Yves frontend.';

    /**
     * @var string
     */
    protected const OPTION_ENVIRONMENT = 'environment';

    /**
     * @var string
     */
    protected const OPTION_ENVIRONMENT_SHORT = 'e';

    /**
     * @var string
     */
    protected const OPTION_ENVIRONMENT_DESCRIPTION = 'Sets the environment to run the command. Currently available environments: production';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addOption(
            static::OPTION_ENVIRONMENT,
            static::OPTION_ENVIRONMENT_SHORT,
            InputOption::VALUE_REQUIRED,
            static::OPTION_ENVIRONMENT_DESCRIPTION,
        );

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
        $this->info('Build Yves frontend');

        $setupFrontendConfigurationTransfer = new SetupFrontendConfigurationTransfer();
        if ($input->getOption(static::OPTION_ENVIRONMENT)) {
            /** @var string|null $environment */
            $environment = $input->getOption(static::OPTION_ENVIRONMENT);
            $setupFrontendConfigurationTransfer->setEnvironment((string)$environment);
        }

        if ($this->getFacade()->buildYvesFrontend($this->getMessenger(), $setupFrontendConfigurationTransfer)) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }
}
