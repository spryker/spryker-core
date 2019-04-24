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
class ModuleCreateConsole extends Console
{
    public const COMMAND_NAME = 'dev:module:create';
    public const ARGUMENT_MODULE = 'module';
    public const ARGUMENT_FILE = 'file';
    public const OPTION_FORCE = 'force';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Create basic core module (Spryker core dev only).');

        $this->addArgument(static::ARGUMENT_MODULE, InputArgument::REQUIRED, 'Name of core module to create or sync. Use "all" for all. Use prefix for vendor namespace, e.g. `SprykerShop.ModuleName`.');
        $this->addArgument(static::ARGUMENT_FILE, InputArgument::OPTIONAL, 'Name of file to create or sync.');
        $this->addOption(static::OPTION_FORCE, 'f', InputOption::VALUE_NONE, 'Force the command, will overwrite existing files.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $this->input->getArgument(static::ARGUMENT_MODULE);

        if ($module !== 'all') {
            $message = 'Create or update Spryker core module ' . $module;
        } else {
            $message = 'Sync all Spryker core modules';
        }

        $this->info($message);

        $options = $this->input->getOptions();
        $options[static::ARGUMENT_FILE] = $this->input->getArgument(static::ARGUMENT_FILE);

        $this->getFacade()->createModule($module, $options);

        return null;
    }
}
