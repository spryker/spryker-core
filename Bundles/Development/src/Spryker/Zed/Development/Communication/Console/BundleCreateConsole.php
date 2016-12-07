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
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class BundleCreateConsole extends Console
{

    const COMMAND_NAME = 'dev:bundle:create';
    const OPTION_BUNDLE = 'from bundle';
    const OPTION_FORCE = 'force (overwrite)';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Create basic core bundle.');

        $this->addArgument(self::OPTION_BUNDLE, InputArgument::REQUIRED, 'Name of core bundle to create or sync. Use "all" for all.');
        $this->addOption(self::OPTION_FORCE, 'f', InputOption::VALUE_NONE, 'Force the command, will overwrite existing files.');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $this->input->getArgument(self::OPTION_BUNDLE);

        if ($bundle !== 'all') {
            $message = 'Create Spryker core bundle ' . $bundle;
        } else {
            $message = 'Sync all Spryker core bundles';
        }

        $this->info($message);

        $options = $this->input->getOptions();

        $this->getFacade()->createBundle($bundle, $options);
    }

}
