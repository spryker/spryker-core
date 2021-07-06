<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Communication\Console;

use Spryker\Zed\Development\Business\Phpstan\PhpstanRunner;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class CodePhpstanConsole extends Console
{
    protected const COMMAND_NAME = 'code:phpstan';
    protected const OPTION_MODULE = PhpstanRunner::OPTION_MODULE;
    protected const OPTION_DRY_RUN = PhpstanRunner::OPTION_DRY_RUN;
    protected const OPTION_LEVEL = PhpstanRunner::OPTION_LEVEL;
    protected const OPTION_OFFSET = PhpstanRunner::OPTION_OFFSET;
    /**
     * @deprecated Not Used
     */
    protected const OPTION_FORMAT = 'format';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Run PHPStan static analyzer for project or core');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of module to run PHPStan for. You can use dot syntax for namespaced ones, e.g. `SprykerEco.FooBar`. `Spryker.all`/`SprykerShop.all` is reserved for CORE internal usage.');
        $this->addOption(static::OPTION_FORMAT, 'f', InputOption::VALUE_OPTIONAL, 'Output format [text, xml, json, md]');
        $this->addOption(static::OPTION_DRY_RUN, 'd', InputOption::VALUE_NONE, 'Dry-run the command, display it only');
        $this->addOption(static::OPTION_LEVEL, 'l', InputOption::VALUE_OPTIONAL, 'Level of rule options - the higher the stricter');

        $description = 'Offset to use for path splitting. Mainly for core, where it is needed for runtime reasons.';
        $description .= PHP_EOL . 'Syntax: {offset}[,{limit}]';
        $this->addOption(static::OPTION_OFFSET, 'o', InputOption::VALUE_OPTIONAL, $description);
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int Exit code
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->getFacade()->runPhpstan($this->input, $this->output);
    }
}
