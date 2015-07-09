<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Console;

use Symfony\Component\Console\Command\Command;
use SprykerEngine\Zed\Kernel\AbstractBundleConfig;

class ConsoleConfig extends AbstractBundleConfig
{

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return [];
    }

}
