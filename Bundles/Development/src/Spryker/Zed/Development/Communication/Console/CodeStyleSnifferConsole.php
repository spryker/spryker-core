<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Spryker\Zed\Development\Business\DevelopmentFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method DevelopmentFacade getFacade()
 */
class CodeStyleSnifferConsole extends Console
{

    const COMMAND_NAME = 'code:sniff';

    const OPTION_BUNDLE = 'bundle';
    const OPTION_DRY_RUN = 'dry-run';
    const OPTION_FIX = 'fix';
    const OPTION_PRINT_DIFF_REPORT = 'report-diff';
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
            ->setDescription('Sniff and fix code style for project or core.');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to fix code style for (or "all").');
        $this->addOption(self::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-Run the command, display it only.');
        $this->addOption(self::OPTION_FIX, 'f', InputOption::VALUE_NONE, 'Automatically fix errors that can be fixed.');
        $this->addOption(self::OPTION_PRINT_DIFF_REPORT, 'r', InputOption::VALUE_NONE, 'Print diff.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
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

        $this->getFacade()->checkCodeStyle($bundle, $this->input->getOptions());
    }

}
