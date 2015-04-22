<?php

namespace SprykerFeature\Zed\Console\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ConsoleBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use Symfony\Component\Console\Command\Command;

/**
 * @method ConsoleBusiness getFactory()
 */
class ConsoleDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()
            ->createConsoleSettings($this->getLocator())
            ->getConsoleCommands();
    }

}
