<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Console;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\Console\ConsoleConfig getConfig()
 */
class ConsoleDependencyProvider extends AbstractBundleDependencyProvider
{
    public const COMMANDS = 'COMMANDS';
    public const EVENT_SUBSCRIBER = 'EVENT_SUBSCRIBER';

    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';

    public const PLUGINS_CONSOLE_PRE_RUN_HOOK = 'PLUGINS_CONSOLE_PRE_RUN_HOOK';
    public const PLUGINS_CONSOLE_POST_RUN_HOOK = 'PLUGINS_CONSOLE_POST_RUN_HOOK';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCommands($container);
        $container = $this->addEventSubscriber($container);
        $container = $this->addApplicationPlugins($container);
        $container = $this->addConsoleHookPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCommands(Container $container)
    {
        $container->set(static::COMMANDS, function (Container $container) {
            return $this->getConsoleCommands($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    protected function getConsoleCommands(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addEventSubscriber(Container $container): Container
    {
        $container->set(static::EVENT_SUBSCRIBER, function (Container $container) {
            return $this->getEventSubscriber($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     */
    protected function getEventSubscriber(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConsoleHookPlugins(Container $container): Container
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
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Console\Dependency\Plugin\ConsolePreRunHookPluginInterface[]
     */
    protected function getConsolePreRunHookPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\Console\Dependency\Plugin\ConsolePostRunHookPluginInterface[]
     */
    protected function getConsolePostRunHookPlugins(Container $container): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATION, function (Container $container) {
            return $this->getApplicationPlugins($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(Container $container): array
    {
        return [];
    }
}
