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
            ->setDescription('Fix code style for project or core.');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to fix code style for (or "all").');
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
