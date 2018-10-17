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
use Zend\Filter\Word\UnderscoreToCamelCase;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class CodeStyleSnifferConsole extends Console
{
    public const COMMAND_NAME = 'code:sniff:style';
    public const OPTION_MODULE = 'module';
    public const OPTION_SNIFFS = 'sniffs';
    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_FIX = 'fix';
    public const OPTION_EXPLAIN = 'explain';
    public const ARGUMENT_SUB_PATH = 'path';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Sniff and fix code style for project or core');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of module to fix code style for. You can use dot syntax for namespaced ones, e.g. `SprykerEco.FooBar`. `Spryker.all`/`SprykerShop.all` is reserved for CORE internal usage.');
        $this->addOption(static::OPTION_SNIFFS, 's', InputOption::VALUE_OPTIONAL, 'Specific sniffs to run, comma separated list of codes');
        $this->addOption(static::OPTION_EXPLAIN, 'e', InputOption::VALUE_NONE, 'Explain the standard by showing the sniffs it includes');
        $this->addOption(static::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-Run the command, display it only');
        $this->addOption(static::OPTION_FIX, 'f', InputOption::VALUE_NONE, 'Automatically fix errors that can be fixed');
        $this->addArgument(static::ARGUMENT_SUB_PATH, InputArgument::OPTIONAL, 'Optional path or sub path element for project level');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $this->input->getOption(static::OPTION_MODULE);
        $path = $this->input->getArgument(static::ARGUMENT_SUB_PATH);

        $this->info($this->buildMessage($module, $path));

        $exitCode = $this->getFacade()->checkCodeStyle($module, $this->input->getOptions() + [static::ARGUMENT_SUB_PATH => $path]);

        return $exitCode;
    }

    /**
     * @param string|null $module
     * @param string|null $path
     *
     * @return string
     */
    protected function buildMessage($module, $path)
    {
        $isCore = strpos($module, '.') !== false;
        $message = sprintf('Run Code Style Sniffer for %s', $isCore ? 'CORE' : 'PROJECT');

        if ($module) {
            $module = $this->normalizeModuleName($module);
            $message .= ' in ' . $module . ' module';
        }

        if ($path) {
            $message .= ' (' . $path . ')';
        }

        return $message;
    }

    /**
     * @param string $module
     *
     * @return string
     */
    protected function normalizeModuleName($module)
    {
        $filter = new UnderscoreToCamelCase();
        $normalized = $filter->filter(str_replace('-', '_', $module));
        $normalized = ucfirst($normalized);

        return $normalized;
    }
}
