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
            $this->getLocator()->git()->consoleAddConsole(),
            $this->getLocator()->git()->consoleCheckoutConsole(),
            $this->getLocator()->git()->consoleCleanConsole(),
            $this->getLocator()->git()->consoleCommitConsole(),
            $this->getLocator()->git()->consoleFetchConsole(),
            $this->getLocator()->git()->consoleMergeConsole(),
            $this->getLocator()->git()->consolePrepareTagConsole(),
            $this->getLocator()->git()->consolePullConsole(),
            $this->getLocator()->git()->consolePullNPushConsole(),
            $this->getLocator()->git()->consolePushConsole(),
            $this->getLocator()->git()->consoleResetConsole(),
            $this->getLocator()->git()->consoleStatusConsole(),
        ];
    }

}
