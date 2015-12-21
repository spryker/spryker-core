<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Git\Business;

use Spryker\Zed\Git\Communication\Console\GitFlowFinishConsole;
use Spryker\Zed\Git\Communication\Console\GitFlowUpdateConsole;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Git\GitConfig;
use Symfony\Component\Console\Command\Command;

/**
 * @method GitConfig getConfig()
 */
class GitBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return [
            $this->createGitFlowUpdateConsole(),
            $this->createGitFlowFinishConsole(),
        ];
    }

    /**
     * @return GitFlowUpdateConsole
     */
    protected function createGitFlowUpdateConsole()
    {
        return new GitFlowUpdateConsole();
    }

    /**
     * @return GitFlowUpdateConsole
     */
    protected function createGitFlowFinishConsole()
    {
        return new GitFlowFinishConsole();
    }

}
