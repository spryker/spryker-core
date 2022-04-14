<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceBridge;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 */
class MessageBrokerDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const EVENT_DISPATCHER = 'dispatcher';

    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_SENDER = 'PLUGINS_MESSAGE_SENDER';

    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_RECEIVER = 'PLUGINS_MESSAGE_RECEIVER';

    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_HANDLER = 'PLUGINS_MESSAGE_HANDLER';

    /**
     * @var string
     */
    public const PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER = 'PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER';

    /**
     * @var string
     */
    public const PLUGINS_MIDDLEWARE = 'PLUGINS_MIDDLEWARE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PLUGINS_EXTERNAL_VALIDATOR = 'PLUGINS_EXTERNAL_VALIDATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->provideEventDispatcher($container);
        $container = $this->provideMessageSenderAdapterPlugins($container);
        $container = $this->provideMessageReceiverAdapterPlugins($container);
        $container = $this->provideMessageHandlerPlugins($container);
        $container = $this->provideMessageAttributeProviderPlugins($container);
        $container = $this->provideMiddlewarePlugins($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * We need to make sure that wherever we use the EventDispatcher we get always the same. That why is is added here
     * and not in the Factory. The Factory would always create a new one.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideEventDispatcher(Container $container): Container
    {
        $container->set(static::EVENT_DISPATCHER, function () {
            return new EventDispatcher();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageSenderAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_SENDER, function () {
            return $this->getMessageSenderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    public function getMessageSenderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageReceiverAdapterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_RECEIVER, function () {
            return $this->getMessageReceiverPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface>
     */
    public function getMessageReceiverPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_HANDLER, function () {
            return $this->getMessageHandlerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMiddlewarePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MIDDLEWARE, function () {
            return $this->getMiddlewarePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface>
     */
    public function getMessageHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Symfony\Component\Messenger\Middleware\MiddlewareInterface>
     */
    public function getMiddlewarePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideMessageAttributeProviderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER, function () {
            return $this->getMessageAttributeProviderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface>
     */
    public function getMessageAttributeProviderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new MessageBrokerToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideExternalValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EXTERNAL_VALIDATOR, function () {
            return $this->getExternalValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface>
     */
    public function getExternalValidatorPlugins(): array
    {
        return [];
    }
}
