<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

use Symfony\Component\Console\Input\InputOption;

class PullConsole extends BaseCommand
{

    const COMMAND_NAME = 'git:pull';
    const OPTION_REBASE = 'rebase';

    protected function configure()
    {
        parent::configure();
        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');

        $this->addOption(self::OPTION_REBASE, null, InputOption::VALUE_NONE, 'rebase on pull');
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
        $command = 'branch=$(git rev-parse --abbrev-ref HEAD) | git pull origin $branch';
        $command = 'git pull origin master';
        if ($this->input->getOption(self::OPTION_REBASE)) {
            $command .= ' --rebase';
        }

        return $command;
    }

}
