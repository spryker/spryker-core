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
    const SERVICE_PROVIDER = 'service provider';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addCommands($container);
        $this->addEventSubscriber($container);
        $this->addServiceProvider($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCommands(Container $container)
    {
        $container[self::COMMANDS] = function (Container $container) {
            return $this->getConsoleCommands($container);
        };
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
     * @return void
     */
    protected function addEventSubscriber(Container $container)
    {
        $container[static::EVENT_SUBSCRIBER] = function (Container $container) {
            return $this->getEventSubscriber($container);
        };
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
     * @return void
     */
    protected function addServiceProvider(Container $container)
    {
        $container[static::SERVICE_PROVIDER] = function (Container $container) {
            return $this->getServiceProvider($container);
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProvider(Container $container)
    {
        return [
            new PropelServiceProvider()
        ];
    }

}
