<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

use Symfony\Component\Console\Input\InputArgument;

class PushConsole extends BaseCommand
{

    const COMMAND_NAME = 'git:push';
    const ARGUMENT_BRANCH_NAME = 'branch';

    /**
     *
     */
    protected function configure()
    {
        parent::configure();
        $this->setName(self::COMMAND_NAME)
            ->setHelp('<info>' . self::COMMAND_NAME . ' -h</info>');

        $this->addArgument(self::ARGUMENT_BRANCH_NAME, InputArgument::REQUIRED, 'branch name');
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
        return 'git push origin ' . $this->input->getArgument(self::ARGUMENT_BRANCH_NAME);
    }

}
