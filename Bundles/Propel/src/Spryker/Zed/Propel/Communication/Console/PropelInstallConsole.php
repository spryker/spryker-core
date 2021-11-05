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
    /**
     * @var string
     */
    public const OPTION_NO_DIFF = 'no-diff';

    /**
     * @var string
     */
    public const OPTION_NO_DIFF_SHORTCUT = 'o';

    /**
     * @var string
     */
    public const OPTION_NO_DIFF_DESCRIPTION = 'Runs without propel:diff';

    /**
     * @var string
     */
    public const COMMAND_NAME = 'propel:install';

    /**
     * @var string
     */
    public const DESCRIPTION = 'Runs config convert, create database, postgres compatibility, copy schemas, runs Diff, build models and migrate tasks';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

        $this->addOption(
            static::OPTION_NO_DIFF,
            static::OPTION_NO_DIFF_SHORTCUT,
            InputOption::VALUE_NONE,
            static::OPTION_NO_DIFF_DESCRIPTION,
        );

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dependingCommands = $this->getDependingCommands();

        foreach ($dependingCommands as $commandName) {
            $this->runDependingCommand($commandName);

            if ($this->hasError()) {
                return $this->getLastExitCode();
            }
        }

        return static::CODE_SUCCESS;
    }

    /**
     * @return array<string>
     */
    protected function getDependingCommands()
    {
        $noDiffOption = $this->input->getOption(static::OPTION_NO_DIFF);

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
     * @param array<string> $commands
     *
     * @return array<string>
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
                    $command,
                ),
            );
        }

        return $filteredCommands;
    }
}
