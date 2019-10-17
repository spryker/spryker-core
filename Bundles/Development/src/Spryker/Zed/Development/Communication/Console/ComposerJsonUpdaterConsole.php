<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class ComposerJsonUpdaterConsole extends AbstractCoreModuleAwareConsole
{
    public const COMMAND_NAME = 'dev:composer:update-json-files';
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
        $modules = $this->getModulesToExecute($input);

        if (!$modules) {
            $this->error(
                sprintf(
                    'Argument `%s` is not a valid module.',
                    $this->input->getArgument(static::ARGUMENT_MODULE)
                )
            );

            return static::CODE_ERROR;
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

        $text = $isDryRun ? 'need(s) updating.' : 'updated.';
        $this->info(sprintf('%s of %s module(s) ' . $text, count($modifiedModules), count($processedModules)));

        if ($this->input->getOption(static::VERBOSE)) {
            foreach ($modifiedModules as $modifiedModule) {
                $this->info('- ' . $modifiedModule);
            }
        }

        if (!$this->input->getOption(static::OPTION_DRY_RUN)) {
            return static::CODE_SUCCESS;
        }

        if (count($modifiedModules)) {
            $commands = [];

            foreach ($modifiedModules as $modifiedModule) {
                $commands[] = 'vendor/bin/console ' . static::COMMAND_NAME . ' ' . $modifiedModule;
            }

            $this->info(
                sprintf(
                    'Please run %s locally without dry-run:',
                    count($commands) > 1 ? 'these commands' : 'this command'
                )
            );
            $this->output->writeln(implode("\n", $commands));
        }

        return count($modifiedModules) < 1 ? static::CODE_SUCCESS : static::CODE_ERROR;
    }
}
