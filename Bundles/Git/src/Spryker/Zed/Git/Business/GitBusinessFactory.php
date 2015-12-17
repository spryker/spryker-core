<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Git\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Git\GitConfig;
use Spryker\Zed\Git\GitDependencyProvider;
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
        return $this->getProvidedDependency(GitDependencyProvider::COMMANDS);
    }

}
