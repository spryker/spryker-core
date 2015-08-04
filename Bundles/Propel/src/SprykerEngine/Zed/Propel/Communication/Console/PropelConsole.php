<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PropelConsole extends Console
{

    const COMMAND_NAME = 'setup:propel';

    const DESCRIPTION = 'This command will run installer for propel2';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runDependingCommand(ConvertConfigConsole::COMMAND_NAME);
        $this->runDependingCommand(CreateDatabaseConsole::COMMAND_NAME);
        $this->runDependingCommand(PostgresqlCompatibilityConsole::COMMAND_NAME);
        $this->runDependingCommand(SchemaCopyConsole::COMMAND_NAME);
        $this->runDependingCommand(BuildModelConsole::COMMAND_NAME);
        $this->runDependingCommand(DiffConsole::COMMAND_NAME);
        $this->runDependingCommand(MigrateConsole::COMMAND_NAME);
    }

    /**
     * @param $command
     * @param array $arguments
     */
    protected function runDependingCommand($command, array $arguments = [])
    {
        $command = $this->getApplication()->find($command);
        $arguments['command'] = $command;
        $input = new ArrayInput($arguments);
        $command->run($input, $this->output);
    }
}
