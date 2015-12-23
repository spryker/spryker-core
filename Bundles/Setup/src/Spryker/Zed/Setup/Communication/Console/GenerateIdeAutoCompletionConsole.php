<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Setup\Communication\Console;

use Spryker\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateIdeAutoCompletionConsole extends Console
{

    const COMMAND_NAME = 'setup:generate-ide-auto-completion';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate ide auto completion files, for all applications [ Client, Yves, Zed ]');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dependingCommands = [
            GenerateClientIdeAutoCompletionConsole::COMMAND_NAME,
            GenerateYvesIdeAutoCompletionConsole::COMMAND_NAME,
            GenerateZedIdeAutoCompletionConsole::COMMAND_NAME,
        ];

        foreach ($dependingCommands as $commandName) {
            $this->runDependingCommand($commandName);

            if ($this->hasError()) {
                return $this->getLastExitCode();
            }
        }
    }

}
