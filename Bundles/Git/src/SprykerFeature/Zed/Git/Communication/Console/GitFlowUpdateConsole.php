<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Communication\Console;

class GitFlowUpdateConsole extends AbstractGitFlowConsole
{

    const COMMAND_NAME = 'gitflow:update';
    const DESCRIPTION = 'Update branch';

    protected function configure()
    {
        parent::configure();

        $this->setName(self::COMMAND_NAME);
        $this->setDescription(self::DESCRIPTION);
    }

    /**
     * @param string $from
     * @param string $branch
     *
     * @return array
     */
    protected function getCommandList($from, $branch)
    {
        return [
            'git checkout ' . $from,
            'git pull --rebase',
            'git checkout ' . $branch,
            'git rebase ' . $from,
            'git push -f origin ' . $branch,
        ];
    }

}
