<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @deprecated 1.0.0 Will be removed in the next major version. Use CodeStyleSnifferConsole instead.
 *
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class CodeStyleFixerConsole extends Console
{

    const COMMAND_NAME = 'code:fix';
    const OPTION_BUNDLE = 'bundle';
    const OPTION_CLEAR = 'clear';
    const OPTION_BUNDLE_ALL = 'all';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Fix code style for project or core');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to fix code style for (or "all")');
        $this->addOption(self::OPTION_CLEAR, 'c', InputOption::VALUE_NONE, 'Force-clear the cache prior to running it');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $bundle = $this->input->getOption(self::OPTION_BUNDLE);
        $message = 'Fix code style in project level';

        if ($bundle) {
            $message = 'Fix code style in all bundles';
            if ($bundle !== self::OPTION_BUNDLE_ALL) {
                $message = 'Check code style in ' . $bundle . ' bundle';
            }
        }
        $this->info($message);

        $this->getFacade()->fixCodeStyle($bundle, $this->input->getOptions());
    }

}
