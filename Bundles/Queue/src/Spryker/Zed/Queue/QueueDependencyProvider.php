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
    /**
     * @var string
     */
    public const CLIENT_QUEUE = 'queue client';

    /**
     * @var string
     */
    public const QUEUE_MESSAGE_PROCESSOR_PLUGINS = 'queue message processor plugin';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'UTIL_ENCODING_SERVICE';

    /**
     * @var string
     */
    public const PLUGINS_QUEUE_MESSAGE_CHECKER = 'PLUGINS_QUEUE_MESSAGE_CHECKER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::CLIENT_QUEUE, function (Container $container) {
            return $container->getLocator()->queue()->client();
        });

        $container->set(static::QUEUE_MESSAGE_PROCESSOR_PLUGINS, function (Container $container) {
            return $this->getProcessorMessagePlugins($container);
        });

        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new QueueToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        $container = $this->addQueueMessageCheckerPlugins($container);

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
     * @return array<\Spryker\Zed\Queue\Dependency\Plugin\QueueMessageProcessorPluginInterface>
     */
    protected function getProcessorMessagePlugins(Container $container)
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\QueueExtension\Dependency\Plugin\QueueMessageCheckerPluginInterface>
     */
    protected function getQueueMessageCheckerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueueMessageCheckerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_QUEUE_MESSAGE_CHECKER, function () {
            return $this->getQueueMessageCheckerPlugins();
        });

        return $container;
    }
}
