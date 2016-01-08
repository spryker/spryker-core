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
class CodePhpMessDetectorConsole extends Console
{

    const COMMAND_NAME = 'code:phpmd';
    const OPTION_BUNDLE = 'bundle';
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
            ->setDescription('Run PHPMD for project or core');

        $this->addOption(self::OPTION_BUNDLE, 'b', InputOption::VALUE_OPTIONAL, 'Name of core bundle to run PHPMD for (or "all")');
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
        $message = 'Run PHPMD in project level';

        if ($bundle) {
            $message = 'Run PHPMD in all bundles';
            if ($bundle !== self::OPTION_BUNDLE_ALL) {
                $message = 'Run PHPMD in ' . $bundle . ' bundle';
            }
        }
        $this->info($message);

        $this->getFacade()->runPhpMd($bundle);
    }

}
