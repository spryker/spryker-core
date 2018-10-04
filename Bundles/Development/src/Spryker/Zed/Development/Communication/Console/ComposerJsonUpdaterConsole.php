<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class ComposerJsonUpdaterConsole extends Console
{
    public const COMMAND_NAME = 'dev:composer:update-json-files';
    public const OPTION_MODULE = 'module';
    public const OPTION_DRY_RUN = 'dry-run';
    public const VERBOSE = 'verbose';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Update composer.json of core modules (Spryker core dev only).');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of core module (comma separated for multiple ones)');
        $this->addOption(static::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-Run the command, display it only, or use in CI');

        $this->setAliases(['dev:dependency:update-composer-files']);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $modules = [];
        $moduleList = $this->input->getOption(static::OPTION_MODULE);
        if ($moduleList) {
            $modules = explode(',', $this->input->getOption(static::OPTION_MODULE));
        }

        $isDryRun = $this->input->getOption(static::OPTION_DRY_RUN);
        $processedModules = $this->getFacade()->updateComposerJsonInModules($modules, $isDryRun);
        $modifiedModules = [];
        foreach ($processedModules as $processedModule => $processed) {
            if (!$processed) {
                continue;
            }
            $modifiedModules[] = $processedModule;
        }

        $text = $isDryRun ? ' need(s) updating.' : 'updated.';
        $this->output->writeln(sprintf('%s of %s module(s) ' . $text, count($modifiedModules), count($processedModules)));

        if ($this->input->getOption(static::VERBOSE)) {
            foreach ($modifiedModules as $modifiedModule) {
                $this->output->writeln('- ' . $modifiedModule);
            }
        }

        if (!$this->input->getOption(static::OPTION_DRY_RUN)) {
            return static::CODE_SUCCESS;
        }

        if (count($modifiedModules)) {
            $command = 'console ' . static::COMMAND_NAME . ' -m ' . implode(',', $modifiedModules);
            $this->output->writeln(sprintf('Please run `%s` locally without dry-run.', $command));
        }

        return count($modifiedModules) < 1 ? static::CODE_SUCCESS : static::CODE_ERROR;
    }
}
