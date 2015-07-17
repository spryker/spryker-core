<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CommitConsole extends BaseCommand
{

    const COMMAND_NAME = 'git:commit';

    const ARGUMENT_MESSAGE = 'message';

    const OPTION_ALL = 'all';
    const OPTION_ALL_SHORT = 'a';

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');

        $this->addArgument(self::ARGUMENT_MESSAGE, InputArgument::REQUIRED, 'commit message');
        $this->addOption(self::OPTION_ALL, self::OPTION_ALL_SHORT, InputOption::VALUE_NONE, 'commit all not added files');
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
        if ($this->input->getOption(self::OPTION_ALL)) {
            $command .= ' -a';
        }
        $message = $this->input->getArgument(self::ARGUMENT_MESSAGE);

        return $command . ' -m "' . $message . '"';
    }

}
