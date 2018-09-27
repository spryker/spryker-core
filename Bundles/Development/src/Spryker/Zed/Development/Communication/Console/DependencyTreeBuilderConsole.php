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
class DependencyTreeBuilderConsole extends Console
{
    public const COMMAND_NAME = 'dev:dependency:build-tree';

    public const OPTION_APPLICATION = 'application';
    public const OPTION_MODULE = 'module';
    public const OPTION_LAYER = 'layer';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Build dependency tree (Spryker core dev only).');

        $this->addOption(static::OPTION_APPLICATION, 'a', InputOption::VALUE_OPTIONAL, 'Name of application (Client, Shared, Yves, Zed, Service)');
        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of core module');
        $this->addOption(static::OPTION_LAYER, 'l', InputOption::VALUE_OPTIONAL, 'Name of layer (only for Zed)');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = '*';

        if ($this->input->getOption(static::OPTION_MODULE)) {
            $module = $this->input->getOption(static::OPTION_MODULE);
        }

        $this->info('Build dependency tree.');

        $this->getFacade()->buildDependencyTree($module);
    }
}
