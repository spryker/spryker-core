<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Git;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;
use SprykerFeature\Zed\Git\Communication\Console\GitFlowFinishConsole;
use SprykerFeature\Zed\Git\Communication\Console\GitFlowUpdateConsole;
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
