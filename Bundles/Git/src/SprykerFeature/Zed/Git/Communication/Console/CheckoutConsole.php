<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CheckoutConsole extends BaseCommand
{

    const COMMAND_NAME = 'git:checkout';

    const ARGUMENT_BRANCH_NAME = 'branch';

    const OPTION_BRANCH = 'branch';
    const OPTION_BRANCH_SHORT = 'b';

    const OPTION_TRACK = 'track';
    const OPTION_TRACK_SHORT = 't';

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');

        $this->addArgument(self::ARGUMENT_BRANCH_NAME, InputArgument::REQUIRED, 'branch name');
        $this->addOption(self::OPTION_BRANCH, self::OPTION_BRANCH_SHORT, InputOption::VALUE_NONE, 'create and checkout a new branch');
        $this->addOption(self::OPTION_TRACK, self::OPTION_TRACK_SHORT, InputOption::VALUE_NONE, 'set upstream info for new branch');
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
        if ($this->input->getOption(self::OPTION_BRANCH)) {
            $command .= ' -b';
        }
        if ($this->input->getOption(self::OPTION_TRACK)) {
            $command .= ' -t';
        }
        $branchName = $this->input->getArgument(self::ARGUMENT_BRANCH_NAME);

        return $command . ' ' . $branchName;
    }

}
