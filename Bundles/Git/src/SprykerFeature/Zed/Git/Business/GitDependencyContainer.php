<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Git\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Spryker\Zed\Git\GitConfig;
use Spryker\Zed\Git\GitDependencyProvider;
use Symfony\Component\Console\Command\Command;

/**
 * @method GitConfig getConfig()
 */
class GitDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getProvidedDependency(GitDependencyProvider::COMMANDS);
    }

}
