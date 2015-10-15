<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git;

use Symfony\Component\Console\Command\Command;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class GitConfig extends AbstractBundleConfig
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return [
            $this->getLocator()->git()->consoleGitFlowUpdateConsole(),
            $this->getLocator()->git()->consoleGitFlowFinishConsole(),
        ];
    }

}
