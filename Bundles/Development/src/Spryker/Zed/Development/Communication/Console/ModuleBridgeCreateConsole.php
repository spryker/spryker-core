<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class ModuleBridgeCreateConsole extends Console
{
    public const COMMAND_NAME = 'dev:bridge:create';

    public const OPTION_BRIDGE_TYPE = 'bridge type';
    public const OPTION_MODULE = 'from module';
    public const OPTION_TO_MODULE = 'to module';

    public const OPTION_METHODS = 'methods';
    public const OPTION_METHODS_SHORT = 'm';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Create a bridge and its interface (facade, query container or client) from one module to another (Spryker core dev only)');

        $this->addArgument(static::OPTION_MODULE, InputArgument::REQUIRED, 'Name of core module where the bridge should be created in. Accepted format is "[VendorName.]ModuleName[.BridgeType]", i.e. "Spryker.MyModule.Facade".');
        $this->addArgument(static::OPTION_TO_MODULE, InputArgument::REQUIRED, 'Name of core module to which the module must be connected to. Accepted format is "[VendorName.]ModuleName[.BridgeType]", i.e. "Spryker.MyModule.Facade".');

        $this->addOption(static::OPTION_METHODS, static::OPTION_METHODS_SHORT, InputOption::VALUE_OPTIONAL, 'Methods to be added to bridge if it already exists, if bridge does not exist, a new bridge with its interface with this comma separated function names is created');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $this->input->getArgument(static::OPTION_MODULE);
        $toModule = $this->input->getArgument(static::OPTION_TO_MODULE);
        $methods = explode(',', $this->input->getOption(static::OPTION_METHODS));

        $message = 'Create bridge in ' . $module;

        $this->info($message);

        $this->getFacade()->createBridge($module, $toModule, $methods);

        return null;
    }
}
