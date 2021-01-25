<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Console;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Application\Application;
use Spryker\Shared\Application\ApplicationInterface;
use Spryker\Shared\Console\Hook\ConsoleRunnerHook;
use Spryker\Shared\Console\Hook\ConsoleRunnerHookInterface;
use Spryker\Shared\Kernel\Container\ContainerProxy;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method \Spryker\Yves\Console\ConsoleConfig getConfig()
 */
class ConsoleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function createApplication(): ApplicationInterface
    {
        return new Application($this->createServiceContainer(), $this->getApplicationPlugins());
    }

    /**
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function createServiceContainer(): ContainerInterface
    {
        return new ContainerProxy(['logger' => null, 'debug' => $this->getConfig()->isDebugModeEnabled(), 'charset' => 'UTF-8']);
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    public function getApplicationPlugins(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::PLUGINS_APPLICATION);
    }

    /**
     * @return \Spryker\Shared\Console\Hook\ConsoleRunnerHookInterface
     */
    public function createConsoleRunnerHook(): ConsoleRunnerHookInterface
    {
        return new ConsoleRunnerHook(
            $this->getPreRunHookPlugins(),
            $this->getPostRunHookPlugins()
        );
    }

    /**
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::COMMANDS);
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function createEventDispatcher(): EventDispatcherInterface
    {
        return new EventDispatcher();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     */
    public function getEventSubscriber(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::EVENT_SUBSCRIBER);
    }

    /**
     * @return \Spryker\Shared\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface[]
     */
    public function getPreRunHookPlugins(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::PLUGINS_CONSOLE_PRE_RUN_HOOK);
    }

    /**
     * @return \Spryker\Shared\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface[]
     */
    public function getPostRunHookPlugins(): array
    {
        return $this->getProvidedDependency(ConsoleDependencyProvider::PLUGINS_CONSOLE_POST_RUN_HOOK);
    }
}
