<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event;

use Spryker\Zed\Event\Dependency\Client\EventToQueueBridge;
use Spryker\Zed\Event\Dependency\EventCollection;
use Spryker\Zed\Event\Dependency\EventSubscriberCollection;
use Spryker\Zed\Event\Dependency\Service\EventToUtilEncoding;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class EventDependencyProvider extends AbstractBundleDependencyProvider
{
    public const EVENT_LISTENERS = 'event_listeners';
    public const EVENT_SUBSCRIBERS = 'event subscribers';

    public const CLIENT_QUEUE = 'client queue';

    public const SERVICE_UTIL_ENCODING = 'service util encoding';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addEventListenerCollection($container);
        $container = $this->addEventSubscriberCollection($container);
        $container = $this->addQueueClient($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getEventListenerCollection()
    {
        return new EventCollection();
    }

    /**
     * @return \Spryker\Zed\Event\Dependency\EventSubscriberCollectionInterface
     */
    public function getEventSubscriberCollection()
    {
        return new EventSubscriberCollection();
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventListenerCollection(Container $container): Container
    {
        $container[static::EVENT_LISTENERS] = function (Container $container) {
            return $this->getEventListenerCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventSubscriberCollection(Container $container): Container
    {
        $container[static::EVENT_SUBSCRIBERS] = function (Container $container) {
            return $this->getEventSubscriberCollection();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueueClient(Container $container): Container
    {
        $container[static::CLIENT_QUEUE] = function (Container $container) {
            return new EventToQueueBridge($container->getLocator()->queue()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new EventToUtilEncoding($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }
}
