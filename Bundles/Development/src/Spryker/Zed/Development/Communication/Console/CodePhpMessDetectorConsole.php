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
class CodePhpMessDetectorConsole extends Console
{
    public const COMMAND_NAME = 'code:phpmd';
    public const OPTION_MODULE = 'module';
    public const OPTION_MODULE_ALL = 'all';
    public const OPTION_DRY_RUN = 'dry-run';
    public const OPTION_FORMAT = 'format';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Run PHPMD for project or core');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of core module to run PHPMD for (or "all")');
        $this->addOption(static::OPTION_FORMAT, 'f', InputOption::VALUE_OPTIONAL, 'Output format [text, xml, html]');
        $this->addOption(static::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-Run the command, display it only');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $this->input->getOption(static::OPTION_MODULE);

        $message = 'Run PHPMD in PROJECT level';
        if ($module) {
            $message = 'Run PHPMD in all CORE modules';
            if ($module !== static::OPTION_MODULE_ALL) {
                $message = 'Run PHPMD in ' . $module . ' CORE module';
            }
        }
        $this->info($message);

        return $this->getFacade()->runPhpMd($module, $this->input->getOptions());
    }
}
