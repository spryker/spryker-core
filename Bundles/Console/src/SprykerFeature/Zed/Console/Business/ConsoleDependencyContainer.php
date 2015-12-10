<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Console\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use SprykerFeature\Zed\Console\ConsoleConfig;
use SprykerFeature\Zed\Console\ConsoleDependencyProvider;
use Symfony\Component\Console\Command\Command;

/**
 * @method ConsoleConfig getConfig()
 */
class ConsoleDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::COMMANDS);
    }

}
