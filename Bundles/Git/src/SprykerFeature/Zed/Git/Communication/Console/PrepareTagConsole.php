<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

use SprykerFeature\Zed\Console\Business\Model\Console;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PrepareTagConsole extends Console
{

    const COMMAND_NAME = 'deploy:prepare-tag';
    const DESCRIPTION = 'Create a tag from master';

    const DEFAULT_TICKET_TEXT = 'HOTFIX';

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
        $ticketText = $this->askTicketText();
        $tagName = $this->getTagName($ticketText);
        $message = $this->askMessage($tagName);

        $tagName = $tagName . '-' . str_replace(' ', '-', $message);
        $this->runCommand('git tag -a ' . $tagName . ' -m "' . $message . '"');

        if ($this->askConfirmation('Push tag')) {
            $this->runCommand('git push origin ' . $tagName);
        }
    }

    /**
     * @param string $command
     */
    protected function runCommand($command)
    {
        $this->printLineSeparator();
        $this->info($command);
        $this->printLineSeparator();
        $process = new Process($command);

        $process->run(
            function ($type, $data) {
                $this->info($data, false);
            }
        );

    }

    /**
     * @return string
     */
    protected function askTicketText()
    {
        $ticketText = $this->ask('Ticket number(s) or name <fg=green>[' . self::DEFAULT_TICKET_TEXT . '</fg=green>]: ', self::DEFAULT_TICKET_TEXT);
        $ticketText = str_replace(['_', ' '], '-', $ticketText);
        $ticketText = str_replace('"', '\'', $ticketText);

        return $ticketText;
    }

    /**
     * @param string $ticketText
     *
     * @return string
     */
    protected function getTagName($ticketText)
    {
        date_default_timezone_set('UTC');
        $tagName = date('Ymd') . '_' . date('Hi') . '-' . $ticketText;

        return $tagName;
    }

    /**
     * @param string $tagName
     *
     * @return string
     */
    protected function askMessage($tagName)
    {
        $message = $this->ask('Message <fg=green>[' . $tagName . '</fg=green>]: ', $tagName);
        $message = str_replace('"', '\'', $message);

        return $message;
    }

}
