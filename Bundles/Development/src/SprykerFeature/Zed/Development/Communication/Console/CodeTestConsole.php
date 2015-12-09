<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Development\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Development\Business\DevelopmentFacade;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method DevelopmentFacade getFacade()
 */
class CodeTestConsole extends Console
{

    const COMMAND_NAME = 'code:test';

    const OPTION_BUNDLE = 'bundle';

    const OPTION_BUNDLE_ALL = 'all';
    const OPTION_INITIALIZE = 'initialize';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Run codecept tests for project or core.');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to run tests for (or "all").');
        $this->addOption(self::OPTION_INITIALIZE, 'i', InputOption::VALUE_NONE, 'Initialize test suite by (re)generating required test classes.');
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

        $message = 'Run codecept tests for project level';
        if ($bundle) {
            $message = 'Run codecept tests for ' . $bundle . ' bundle';
        }
        $this->info($message);

        $initialize = $this->input->getOption(self::OPTION_INITIALIZE);
        if (!$initialize) {
            $this->warning('Make sure you ran `codecept build` already.');
        }

        $this->getFacade()->runTest($bundle, $this->input->getOptions());
    }

}
