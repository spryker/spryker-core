<?php

namespace SprykerFeature\Zed\Git\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\GitBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use Symfony\Component\Console\Command\Command;

/**
 * @method GitBusiness getFactory()
 */
class GitDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->createGitSettings($this->getLocator())->getConsoleCommands();
    }
}
