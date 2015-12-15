<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Git\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Command\Command;

/**
 * @method GitDependencyContainer getDependencyContainer()
 */
class GitFacade extends AbstractFacade
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getDependencyContainer()->getConsoleCommands();
    }

}
