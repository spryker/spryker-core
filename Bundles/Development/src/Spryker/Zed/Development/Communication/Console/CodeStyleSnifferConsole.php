<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class CodeStyleSnifferConsole extends Console
{

    const COMMAND_NAME = 'code:sniff';
    const OPTION_BUNDLE = 'bundle';
    const OPTION_SNIFFS = 'sniffs';
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_FIX = 'fix';
    const OPTION_BUNDLE_ALL = 'all';
    const ARGUMENT_SUB_PATH = 'path';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Sniff and fix code style for project or core');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to fix code style for (or "all")');
        $this->addOption(self::OPTION_SNIFFS, 's', InputOption::VALUE_OPTIONAL, 'Specific sniffs to run, comma separated list of codes');
        $this->addOption(self::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-Run the command, display it only');
        $this->addOption(self::OPTION_FIX, 'f', InputOption::VALUE_NONE, 'Automatically fix errors that can be fixed');
        $this->addArgument(self::ARGUMENT_SUB_PATH, InputArgument::OPTIONAL, 'Optional path or sub path element for project level');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $this->input->getOption(self::OPTION_BUNDLE);

        $message = 'Check code style in project level';
        if ($bundle) {
            $message = 'Check code style in all bundles';
            if ($bundle !== self::OPTION_BUNDLE_ALL) {
                $message = 'Check code style in ' . $bundle . ' bundle';
            }
        }
        $this->info($message);

        $path = $this->input->getArgument(static::ARGUMENT_SUB_PATH);
        if ($bundle && $path) {
            $this->error('Path is only valid for project level');
            return self::CODE_ERROR;
        }

        return $this->getFacade()->checkCodeStyle($bundle, $this->input->getOptions() + [static::ARGUMENT_SUB_PATH => $path]);
    }

}
