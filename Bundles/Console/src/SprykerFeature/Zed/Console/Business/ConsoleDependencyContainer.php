<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Console\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\ConsoleBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractDependencyContainer;
use SprykerFeature\Zed\Console\ConsoleConfig;
use Symfony\Component\Console\Command\Command;

/**
 * @method ConsoleBusiness getFactory()
 * @method ConsoleConfig getConfig()
 */
class ConsoleDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getConfig()->getConsoleCommands();
    }

}
