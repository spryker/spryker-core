<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Console;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use Symfony\Component\Console\Command\Command;

class ConsoleDependencyProvider extends AbstractBundleDependencyProvider
{

    const COMMANDS = 'commands';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::COMMANDS] = function (Container $container) {
            return $this->getConsoleCommands($container);
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Command[]
     */
    public function getConsoleCommands(Container $container)
    {
        return [];
    }

}
