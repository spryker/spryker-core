<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\NewRelic\Communication\Plugin\NewRelicConsolePlugin;

class ConsoleDependencyProvider extends AbstractBundleDependencyProvider
{

    const COMMANDS = 'commands';
    const EVENT_SUBSCRIBER = 'event_subscriber';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addCommands($container);
        $container = $this->addEventSubscriber($container);

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
        return [
            $this->createNewRelicConsolePlugin()
        ];
    }

    /**
     * @deprecated This will be removed with next major. If you want to use the NewRelic feature add plugin to
     * projects ConsoleDependencyProvider.
     *
     * @return \Spryker\Zed\NewRelic\Communication\Plugin\NewRelicConsolePlugin
     */
    private function createNewRelicConsolePlugin()
    {
        return new NewRelicConsolePlugin();
    }

}
