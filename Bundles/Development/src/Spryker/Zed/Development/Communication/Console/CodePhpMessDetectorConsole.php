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
 * @method \Spryker\Zed\Development\Business\DevelopmentFacade getFacade()
 */
class CodePhpMessDetectorConsole extends Console
{

    const COMMAND_NAME = 'code:phpmd';
    const OPTION_BUNDLE = 'bundle';
    const OPTION_BUNDLE_ALL = 'all';
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_FORMAT = 'format';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Run PHPMD for project or core');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to run PHPMD for (or "all")');
        $this->addOption(self::OPTION_FORMAT, 'f', InputOption::VALUE_OPTIONAL, 'Output format [text, xml, html]');
        $this->addOption(self::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-Run the command, display it only');
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
        $message = 'Run PHPMD in project level';

        if ($bundle) {
            $message = 'Run PHPMD in all bundles';
            if ($bundle !== self::OPTION_BUNDLE_ALL) {
                $message = 'Run PHPMD in ' . $bundle . ' bundle';
            }
        }
        $this->info($message);

        return $this->getFacade()->runPhpMd($bundle, $this->input->getOptions());
    }

}
