<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git\Business;

use Generated\Zed\Ide\FactoryAutoCompletion\GitBusiness;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;
use Symfony\Component\Console\Command\Command;

/**
 * @method GitBusiness getFactory()
 * @method GitConfig getConfig()
 */
class GitDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getConfig()->getConsoleCommands();
    }

}
