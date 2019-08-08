<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Queue;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Queue\Dependency\Service\QueueToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\Queue\QueueConfig getConfig()
 */
class QueueDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUEUE = 'queue client';
    public const QUEUE_MESSAGE_PROCESSOR_PLUGINS = 'queue message processor plugin';
    public const SERVICE_UTIL_ENCODING = 'UTIL_ENCODING_SERVICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::CLIENT_QUEUE] = function (Container $container) {
            return $container->getLocator()->queue()->client();
        };

        $container[static::QUEUE_MESSAGE_PROCESSOR_PLUGINS] = function (Container $container) {
            return $this->getProcessorMessagePlugins($container);
        };

        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new QueueToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };

        return $container;
    }

    /**
     * For processing the received messages from the queue, plugins can be
     * registered here by having queue name as a key. All plugins need to implement
     * Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface
     *
     *  e.g: 'mail' => new MailQueueMessageProcessorPlugin()
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface[]
     */
    protected function getProcessorMessagePlugins(Container $container)
    {
        return [];
    }
}
