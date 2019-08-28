<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class PropelInstallConsole extends Console
{
    public const OPTION_NO_DIFF = 'no-diff';
    public const OPTION_NO_DIFF_SHORTCUT = 'o';
    public const OPTION_NO_DIFF_DESCRIPTION = 'Runs without propel:diff';

    public const COMMAND_NAME = 'propel:install';
    public const DESCRIPTION = 'Runs config convert, create database, postgres compatibility, copy schemas, runs Diff, build models and migrate tasks';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);

        $this->addOption(
            self::OPTION_NO_DIFF,
            self::OPTION_NO_DIFF_SHORTCUT,
            InputOption::VALUE_NONE,
            self::OPTION_NO_DIFF_DESCRIPTION
        );

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dependingCommands = $this->getDependingCommands();

        foreach ($dependingCommands as $commandName) {
            $this->runDependingCommand($commandName);

            if ($this->hasError()) {
                return $this->getLastExitCode();
            }
        }
    }

    /**
     * @return string[]
     */
    protected function getDependingCommands()
    {
        $noDiffOption = $this->input->getOption(self::OPTION_NO_DIFF);

        $dependingCommands = [
            CreateDatabaseConsole::COMMAND_NAME,
            PostgresqlCompatibilityConsole::COMMAND_NAME,
            SchemaCopyConsole::COMMAND_NAME,
            BuildModelConsole::COMMAND_NAME,
            EntityTransferGeneratorConsole::COMMAND_NAME,
        ];
        if ($noDiffOption === false) {
            $dependingCommands[] = DiffConsole::COMMAND_NAME;
        }
        $dependingCommands[] = MigrateConsole::COMMAND_NAME;

        return $this->filterOutNonRegisteredCommands($dependingCommands);
    }

    /**
     * @param string[] $commands
     *
     * @return string[]
     */
    protected function filterOutNonRegisteredCommands(array $commands): array
    {
        $filteredCommands = [];

        foreach ($commands as $command) {
            if ($this->getApplication()->has($command)) {
                $filteredCommands[] = $command;

                continue;
            }

            $this->output->writeln(
                sprintf(
                    '<fg=red>There is no command defined with the name "%s". Make sure the command was registered properly.</>',
                    $command
                )
            );
        }

        return $filteredCommands;
    }
}
