<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Console\ConsoleConfig getConfig()
 */
class ConsoleDependencyProvider extends AbstractBundleDependencyProvider
{
    public const COMMANDS = 'commands';
    public const EVENT_SUBSCRIBER = 'event_subscriber';

    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';

    /**
     * @deprecated Use `\Spryker\Zed\Console\ConsoleDependencyProvider::APPLICATION_PLUGINS` instead.
     */
    public const SERVICE_PROVIDERS = 'service providers';

    public const PLUGINS_CONSOLE_PRE_RUN_HOOK = 'PLUGINS_CONSOLE_PRE_RUN_HOOK';
    public const PLUGINS_CONSOLE_POST_RUN_HOOK = 'PLUGINS_CONSOLE_POST_RUN_HOOK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCommands($container);
        $container = $this->addEventSubscriber($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addServiceProviders($container);
        $container = $this->addConsoleHookPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCommands(Container $container)
    {
        $container->set(static::COMMANDS, function (Container $container) {
            return $this->getConsoleCommands($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getConsoleCommands(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventSubscriber(Container $container)
    {
        $container->set(static::EVENT_SUBSCRIBER, function (Container $container) {
            return $this->getEventSubscriber($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     */
    protected function getEventSubscriber(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addConsoleHookPlugins(Container $container)
    {
        $container->set(static::PLUGINS_CONSOLE_PRE_RUN_HOOK, function (Container $container) {
            return $this->getConsolePreRunHookPlugins($container);
        });

        $container->set(static::PLUGINS_CONSOLE_POST_RUN_HOOK, function (Container $container) {
            return $this->getConsolePostRunHookPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface[]
     */
    protected function getConsolePreRunHookPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface[]
     */
    protected function getConsolePostRunHookPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATION, function (Container $container) {
            return $this->getApplicationPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @deprecated Use `\Spryker\Zed\Console\ConsoleDependencyProvider::addApplicationPlugins()` instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServiceProviders(Container $container)
    {
        $container->set(static::SERVICE_PROVIDERS, function (Container $container) {
            return $this->getServiceProviders($container);
        });

        return $container;
    }

    /**
     * @deprecated Use `\Spryker\Zed\Console\ConsoleDependencyProvider::getApplicationPlugins()` instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProviders(Container $container)
    {
        return [];
    }
}
