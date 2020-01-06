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
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class CodeTestConsole extends Console
{
    public const COMMAND_NAME = 'code:test';

    public const OPTION_MODULE = 'module';
    public const OPTION_INITIALIZE = 'initialize';
    public const OPTION_GROUP = 'group';
    public const OPTION_TYPE_EXCLUDE = 'exclude';
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Run codecept tests for project or core');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of core module to run tests for (or "Spryker*.all")');
        $this->addOption(static::OPTION_GROUP, 'g', InputOption::VALUE_OPTIONAL, 'Groups of tests to be executed (multiple values allowed, comma separated)');
        $this->addOption(static::OPTION_TYPE_EXCLUDE, 'x', InputOption::VALUE_OPTIONAL, 'Types of tests to be skipped (e.g. Presentation; multiple values allowed, comma separated)');
        $this->addOption(static::OPTION_INITIALIZE, 'i', InputOption::VALUE_NONE, 'Initialize test suite by (re)generating required test classes');
        $this->addOption(static::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-run the command, only output the commands that would be run');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $module = $this->input->getOption(static::OPTION_MODULE);
        $this->displayRunInfo($input, $module);

        if (!$this->input->getOption(static::OPTION_INITIALIZE) && !$input->getOption(static::OPTION_DRY_RUN)) {
            $this->warning('Make sure you ran `codecept build` already.');
        }

        return $this->getFacade()->runTest(
            $module,
            $this->extendOptions($input)
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     *
     * @return array
     */
    protected function extendOptions(InputInterface $input): array
    {
        $options = $input->getOptions();

        return $options;
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param string|null $module
     *
     * @return void
     */
    protected function displayRunInfo(InputInterface $input, ?string $module): void
    {
        $message = 'Run codecept tests for project level';
        if ($module) {
            $message = 'Run codecept tests for module(s): ' . $module;
        }
        if ($input->getOption(static::OPTION_DRY_RUN)) {
            $message .= ' [dry run]';
        }
        $this->info($message);
    }
}
