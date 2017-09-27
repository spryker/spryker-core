<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Communication\Plugin\ServiceProvider\PropelServiceProvider;

class ConsoleDependencyProvider extends AbstractBundleDependencyProvider
{

    const COMMANDS = 'commands';
    const EVENT_SUBSCRIBER = 'event_subscriber';
    const SERVICE_PROVIDERS = 'service providers';

    const PLUGINS_CONSOLE_PRE_RUN_HOOK = 'PLUGINS_CONSOLE_PRE_RUN_HOOK';
    const PLUGINS_CONSOLE_POST_RUN_HOOK = 'PLUGINS_CONSOLE_POST_RUN_HOOK';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCommands($container);
        $container = $this->addEventSubscriber($container);
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
        $container[self::COMMANDS] = function (Container $container) {
            return $this->getConsoleCommands($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands(Container $container)
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
        $container[static::EVENT_SUBSCRIBER] = function (Container $container) {
            return $this->getEventSubscriber($container);
        };

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
        $container[static::PLUGINS_CONSOLE_PRE_RUN_HOOK] = function (Container $container) {
            return $this->getConsolePreRunHookPlugins($container);
        };

        $container[static::PLUGINS_CONSOLE_POST_RUN_HOOK] = function (Container $container) {
            return $this->getConsolePostRunHookPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface[]
     */
    public function getConsolePreRunHookPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface[]
     */
    public function getConsolePostRunHookPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServiceProviders(Container $container)
    {
        $container[static::SERVICE_PROVIDERS] = function (Container $container) {
            return $this->getServiceProviders($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProviders(Container $container)
    {
        return [
            new PropelServiceProvider()
        ];
    }

}
