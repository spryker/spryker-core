<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

class CleanConsole extends BaseCommand
{

    const COMMAND_NAME = 'git:clean';

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');

    }

    /**
     *
     */
    protected function sendCommandMessage()
    {
        $this->info('Run git command for project-root and "' . $this->getCommaSeparatedPackages() . '" spryker packages.',
            false
        );
    }

    /**
     * @return string
     */
    protected function computeCommand()
    {
        $command = str_replace(':', ' ', self::COMMAND_NAME);
        $command .= ' -df';

        return $command;
    }

}
