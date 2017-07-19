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
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class DependencyTreeBuilderConsole extends Console
{

    const COMMAND_NAME = 'dev:dependency:build-tree';

    const OPTION_APPLICATION = 'application';
    const OPTION_BUNDLE = 'module';
    const OPTION_LAYER = 'layer';

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

        $this->addOption(static::OPTION_APPLICATION, 'a', InputOption::VALUE_OPTIONAL, 'Name of application to build the dependency tree (Client, Shared, Yves, Zed)');
        $this->addOption(static::OPTION_BUNDLE, 'm', InputOption::VALUE_OPTIONAL, 'Name of module to build the dependency tree');
        $this->addOption(static::OPTION_LAYER, 'l', InputOption::VALUE_OPTIONAL, 'Name of layer to build the dependency tree (only for Zed)');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $application = '*';
        $bundle = '*';
        $layer = '*';

        if ($this->input->getOption(static::OPTION_APPLICATION)) {
            $application = $this->input->getOption(static::OPTION_APPLICATION);
        }
        if ($this->input->getOption(static::OPTION_BUNDLE)) {
            $bundle = $this->input->getOption(static::OPTION_BUNDLE);
        }
        if ($this->input->getOption(static::OPTION_LAYER)) {
            $layer = $this->input->getOption(static::OPTION_LAYER);
        }

        $this->info(sprintf(
            'Build dependency tree.',
            $application,
            $bundle,
            $layer
        ));

        $this->getFacade()->buildDependencyTree($application, $bundle, $layer);
    }

}
