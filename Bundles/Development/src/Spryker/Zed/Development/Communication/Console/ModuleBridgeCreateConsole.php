<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class ModuleBridgeCreateConsole extends Console
{
    const COMMAND_NAME = 'dev:bridge:create';
    const OPTION_MODULE = 'from module';
    const OPTION_TO_MODULE = 'to module';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Create bridge and facade interface (Spryker core dev only)');

        $this->addArgument(static::OPTION_MODULE, InputArgument::REQUIRED, 'Name of core module where the bridge should be created in');
        $this->addArgument(static::OPTION_TO_MODULE, InputArgument::REQUIRED, 'Name of core module to which the module must be connected to');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $this->input->getArgument(static::OPTION_MODULE);
        $toModule = $this->input->getArgument(static::OPTION_TO_MODULE);

        $message = 'Create bridge in ' . $module;

        $this->info($message);

        $this->getFacade()->createBridge($module, $toModule);
    }
}
