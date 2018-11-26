<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class MigrationCheckConsole extends Console
{
    public const COMMAND_NAME = 'propel:migration:check';
    public const CODE_CHANGES = 3;

    public const MIGRATION_NEEDS_TO_BE_EXECUTED_MESSAGE = 'migration needs to be executed';
    public const MIGRATIONS_NEED_TO_BE_EXECUTED_MESSAGE = 'migrations need to be executed';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription('Checks if migration needs to be executed. Scripts can use return code ' . static::CODE_SUCCESS . ' (all good) vs ' . static::CODE_CHANGES . ' (migration needed).');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Checking if migration is needed:' . PHP_EOL);

        $config = Config::get(PropelConstants::PROPEL);
        $command = 'APPLICATION_ENV=' . APPLICATION_ENV
            . ' APPLICATION_STORE=' . APPLICATION_STORE
            . ' APPLICATION_ROOT_DIR=' . APPLICATION_ROOT_DIR
            . ' APPLICATION=' . APPLICATION
            . ' vendor/bin/propel status --config-dir '
            . $config['paths']['phpConfDir'];

        $process = new Process($command, APPLICATION_ROOT_DIR);
        $process->run();

        $processOutput = $process->getOutput();

        $migrationNeeded = $this->checkIfMigrationRunNeeded($processOutput);

        if ($migrationNeeded) {
            $output->writeln($processOutput, OutputInterface::VERBOSITY_VERBOSE);

            $output->writeln('<error>migration needed</error>');

            return static::CODE_CHANGES;
        }

        $style = new OutputFormatterStyle('black', 'green');
        $this->output->getFormatter()->setStyle('success', $style);
        $output->writeln('<success>migration not needed</success>');

        return static::CODE_SUCCESS;
    }

    /**
     * @param string $processOutput
     *
     * @return bool
     */
    protected function checkIfMigrationRunNeeded(string $processOutput): bool
    {
        if (strpos($processOutput, static::MIGRATION_NEEDS_TO_BE_EXECUTED_MESSAGE) !== false ||
            strpos($processOutput, static::MIGRATIONS_NEED_TO_BE_EXECUTED_MESSAGE) !== false) {
            return true;
        }

        return false;
    }
}
