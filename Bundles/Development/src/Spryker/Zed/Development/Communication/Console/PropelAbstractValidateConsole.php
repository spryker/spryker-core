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
 * @internal Core Development internal only.
 *
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 */
class PropelAbstractValidateConsole extends Console
{
    protected const COMMAND_NAME = 'code:propel:validate-abstract';
    protected const OPTION_MODULE = 'module';
    protected const OPTION_STRICT = 'strict';

    /**
     * @return void
     */
    protected function configure()
    {
        parent::configure();

        $this
            ->setName(static::COMMAND_NAME)
            ->setHelp('<info>' . static::COMMAND_NAME . ' -h</info>')
            ->setDescription('Check Abstract classes for Propel tables exist in core');

        $this->addOption(static::OPTION_MODULE, 'm', InputOption::VALUE_OPTIONAL, 'Name of module to run validation for.');
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
        $message = $this->buildMessage($module);

        $this->info($message);

        $result = $this->getFacade()->runPropelAbstractValidation($output, $module);
        if ($result) {
            return static::CODE_SUCCESS;
        }

        return static::CODE_ERROR;
    }

    /**
     * @param string|null $module
     *
     * @return string
     */
    protected function buildMessage(?string $module): string
    {
        $message = 'Run Propel Abstract classes validation';

        if ($module) {
            $message .= ' for <fg=yellow>' . $module . '</> module';
        }

        return $message;
    }
}
