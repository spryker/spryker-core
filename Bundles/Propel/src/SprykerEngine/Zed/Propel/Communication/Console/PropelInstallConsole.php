<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PropelInstallConsole extends Console
{

    const OPTION_NO_DIFF = 'diff';
    const OPTION_NO_DIFF_SHORT = 'd';
    const OPTION_NO_DIFF_DESCRIPTION = 'not running diff [--diff n]';
    const OPTION_NO_DIFF_VALUE = 'n';

    const COMMAND_NAME = 'propel:install';
    const COMMAND_NAME_NO_DIFF = 'propel:install --diff n';

    const DESCRIPTION = 'Runs config convert, create database, postgres compatibility, copy schemas, runs Diff, build models and migrate tasks';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addOption(
            self::OPTION_NO_DIFF,
            self::OPTION_NO_DIFF_SHORT,
            InputOption::VALUE_OPTIONAL,
            self::OPTION_NO_DIFF_DESCRIPTION
        );

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $diffOption = $this->input->getOption(self::OPTION_NO_DIFF);

        $this->runDependingCommand(ConvertConfigConsole::COMMAND_NAME);
        $this->runDependingCommand(CreateDatabaseConsole::COMMAND_NAME);
        $this->runDependingCommand(PostgresqlCompatibilityConsole::COMMAND_NAME);
        $this->runDependingCommand(SchemaCopyConsole::COMMAND_NAME);
        $this->runDependingCommand(BuildModelConsole::COMMAND_NAME);

        if ($diffOption !== self::OPTION_NO_DIFF_VALUE) {
            $this->runDependingCommand(DiffConsole::COMMAND_NAME);
        }

        $this->runDependingCommand(MigrateConsole::COMMAND_NAME);
    }

    /**
     * @param string $command
     * @param array $arguments
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function runDependingCommand($command, array $arguments = [])
    {
        $command = $this->getApplication()->find($command);
        $arguments['command'] = $command;
        $input = new ArrayInput($arguments);
        $command->run($input, $this->output);
    }

}
