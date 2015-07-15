<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Setup\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateIdeAutoCompletionConsole extends Console
{

    const COMMAND_NAME = 'setup:generate-ide-auto-completion';

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Generate ide auto completion files, for all applications [ Client, Yves, Zed ]');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->runDependingCommand(GenerateClientIdeAutoCompletionConsole::COMMAND_NAME);
        $this->info('Client-Files successfully created.', false);
        $this->runDependingCommand(GenerateYvesIdeAutoCompletionConsole::COMMAND_NAME);
        $this->info('Yves-Files successfully created.', false);
        $this->runDependingCommand(GenerateZedIdeAutoCompletionConsole::COMMAND_NAME);
        $this->info('Zed-Files successfully created.', false);
    }

}
