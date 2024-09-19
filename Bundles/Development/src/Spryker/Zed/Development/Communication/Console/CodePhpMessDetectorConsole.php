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
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class CodePhpMessDetectorConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'code:phpmd';

    /**
     * @var string
     */
    public const OPTION_MODULE = 'module';

    /**
     * @var string
     */
    public const OPTION_MODULE_ALL = 'all';

    /**
     * @var string
     */
    public const OPTION_DRY_RUN = 'dry-run';

    /**
     * @var string
     */
    public const OPTION_FORMAT = 'format';

    /**
     * @return void
     */
    protected function configure(): void
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
        /** @var string|null $module */
        $module = $this->input->getOption(static::OPTION_MODULE);

        $message = $this->buildMessage($module);
        $this->info($message);

        return $this->getFacade()->runPhpMd($module, $this->input->getOptions());
    }

    /**
     * @param string|null $module
     *
     * @return string
     */
    protected function buildMessage(?string $module = null): string
    {
        $message = 'Run PHPMD in';
        if ($this->getFactory()->getConfig()->isStandaloneMode()) {
            return sprintf('%s Standalone Mode', $message);
        }

        if ($module === null) {
            return sprintf('%s PROJECT level', $message);
        }

        if ($module !== static::OPTION_MODULE_ALL) {
            return sprintf('%s %s CORE module', $message, $module);
        }

        return sprintf('%s all CORE modules', $message);
    }
}
