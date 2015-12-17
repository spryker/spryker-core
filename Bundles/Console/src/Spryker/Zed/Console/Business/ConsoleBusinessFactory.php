<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Console\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Console\ConsoleConfig;
use Spryker\Zed\Console\ConsoleDependencyProvider;
use Symfony\Component\Console\Command\Command;

/**
 * @method ConsoleConfig getConfig()
 */
class ConsoleBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::COMMANDS);
    }

}
