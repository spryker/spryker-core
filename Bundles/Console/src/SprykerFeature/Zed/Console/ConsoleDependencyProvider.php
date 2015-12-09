<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Console;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use Symfony\Component\Console\Command\Command;

class ConsoleDependencyProvider extends AbstractBundleDependencyProvider
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return [];
    }

}
