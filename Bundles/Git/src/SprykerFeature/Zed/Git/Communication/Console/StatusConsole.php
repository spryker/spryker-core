<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

use Symfony\Component\Console\Input\InputOption;

class StatusConsole extends BaseCommand
{

    const COMMAND_NAME = 'git:status';

    const OPTION_SHORT = 'short';
    const OPTION_SHORT_SHORT = 's';
    const OPTION_BRANCH = 'branch';
    const OPTION_BRANCH_SHORT = 'b';

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>git-status -h</info>');

        $this->addOption(self::OPTION_SHORT, self::OPTION_SHORT_SHORT, InputOption::VALUE_NONE, 'show status concisely');
        $this->addOption(self::OPTION_BRANCH, self::OPTION_BRANCH_SHORT, InputOption::VALUE_NONE, 'show branch information');
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
        if ($this->input->getOption(self::OPTION_SHORT)) {
            $command .= ' -s';
        }
        if ($this->input->getOption(self::OPTION_BRANCH)) {
            $command .= ' -b';
        }

        return $command;
    }

}
