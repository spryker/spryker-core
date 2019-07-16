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
class CodeFixturesConsole extends Console
{
    public const COMMAND_NAME = 'code:fixtures';
    public const OPTION_MODULE = 'module';
    public const OPTION_MODULE_ALL = 'all';
    public const OPTION_INITIALIZE = 'initialize';
    public const OPTION_GROUP = 'group';
    public const OPTION_TYPE_EXCLUDE = 'exclude';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Build fixtures for codeception tests');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of core module to build fixtures for (or "all")');
        $this->addOption(static::OPTION_GROUP, 'g', InputOption::VALUE_OPTIONAL, 'Groups of fixtures to be build (multiple values allowed, comma separated)');
        $this->addOption(static::OPTION_TYPE_EXCLUDE, 'x', InputOption::VALUE_OPTIONAL, 'Types of fixtures to be skipped (e.g. Slow; multiple values allowed, comma separated)');
        $this->addOption(static::OPTION_INITIALIZE, 'i', InputOption::VALUE_NONE, 'Initialize actors by (re)generating required classes');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $this->input->getOption(static::OPTION_MODULE);

        $message = 'Build fixtures for codeception tests for project level';
        if ($module) {
            $message = 'Build fixtures for codeception tests for ' . $module . ' module';
        }
        $this->info($message);

        $initialize = $this->input->getOption(static::OPTION_INITIALIZE);
        if (!$initialize) {
            $this->warning('Make sure you ran `codecept build` already.');
        }

        $this->getFacade()->runFixtures($module, $this->input->getOptions());
    }
}
