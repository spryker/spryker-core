<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use SprykerFeature\Zed\Maintenance\Business\MaintenanceFacade;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method MaintenanceFacade getFacade()
 */
class CodeStyleFixerConsole extends Console
{

    const COMMAND_NAME = 'code:fix-style';

    const ARGUMENT_BUNDLE = 'bundle';

    const OPTION_CLEAR = 'clear';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>')
            ->setDescription('Fix code style for a specific bundle')
        ;

        $this->addArgument(self::ARGUMENT_BUNDLE, InputArgument::OPTIONAL, 'Name of bundle to fix code style');
        $this->addOption(self::OPTION_CLEAR, 'c', InputOption::VALUE_NONE, 'Force-clear the cache prior to running it');
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
        $bundle = $this->input->getArgument(self::ARGUMENT_BUNDLE);
        if (!$bundle) {
            $this->info('Fix code style in all bundles');
        } else {
            $this->info('Fix code style in ' . $this->input->getArgument(self::ARGUMENT_BUNDLE) . ' bundle');
        }

        $clear = $this->input->getOption(self::OPTION_CLEAR);
        $this->getFacade()->fixCodeStyle($bundle, $clear);
    }

}
