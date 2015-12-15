<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Git;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Git\Communication\Console\GitFlowFinishConsole;
use Spryker\Zed\Git\Communication\Console\GitFlowUpdateConsole;
use Symfony\Component\Console\Command\Command;

class GitDependencyProvider extends AbstractBundleDependencyProvider
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
        return [
            new GitFlowUpdateConsole(),
            new GitFlowFinishConsole(),
        ];
    }

}
