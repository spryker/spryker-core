<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

class FetchConsole extends BaseCommand
{

    const COMMAND_NAME = 'git:fetch';

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
        $this->info(
            'Run git command for project-root and "' . $this->getCommaSeparatedPackages() . '" spryker packages.',
            false
        );
    }

    /**
     * @return string
     */
    protected function computeCommand()
    {
        return 'git fetch';
    }

}
