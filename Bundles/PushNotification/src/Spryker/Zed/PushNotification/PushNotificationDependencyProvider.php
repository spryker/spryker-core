<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PushNotification\Dependency\Facade\PushNotificationToLocaleFacadeBridge;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilEncodingServiceBridge;
use Spryker\Zed\PushNotification\Dependency\Service\PushNotificationToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\PushNotification\PushNotificationConfig getConfig()
 */
class PushNotificationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @var string
     */
    public const PLUGINS_PUSH_NOTIFICATION_SUBSCRIPTION_VALIDATOR = 'PLUGINS_PUSH_NOTIFICATION_SUBSCRIPTION_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_PUSH_NOTIFICATION_VALIDATOR = 'PLUGINS_PUSH_NOTIFICATION_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_PUSH_NOTIFICATION_PRE_SEND = 'PLUGINS_PUSH_NOTIFICATION_PRE_SEND';

    /**
     * @var string
     */
    public const PLUGINS_PUSH_NOTIFICATION_SENDER = 'PLUGINS_PUSH_NOTIFICATION_SENDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addLocaleFacade($container);

        $container = $this->addUtilTextService($container);
        $container = $this->addUtilEncodingService($container);

        $container = $this->addPushNotificationSubscriptionValidatorPlugins($container);
        $container = $this->addPushNotificationValidatorPlugins($container);
        $container = $this->addPushNotificationPreSendPlugins($container);
        $container = $this->addPushNotificationSenderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new PushNotificationToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new PushNotificationToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new PushNotificationToUtilEncodingServiceBridge(
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
    protected function addPushNotificationSubscriptionValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUSH_NOTIFICATION_SUBSCRIPTION_VALIDATOR, function () {
            return $this->getPushNotificationSubscriptionValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPushNotificationValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUSH_NOTIFICATION_VALIDATOR, function () {
            return $this->getPushNotificationValidatorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationValidatorPluginInterface>
     */
    protected function getPushNotificationValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPushNotificationPreSendPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUSH_NOTIFICATION_PRE_SEND, function () {
            return $this->getPushNotificationPreSendPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPushNotificationSenderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PUSH_NOTIFICATION_SENDER, function () {
            return $this->getPushNotificationSenderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSubscriptionValidatorPluginInterface>
     */
    protected function getPushNotificationSubscriptionValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationPreSendPluginInterface>
     */
    protected function getPushNotificationPreSendPlugins(): array
    {
        return [];
    }

    /**
     * @return list<\Spryker\Zed\PushNotificationExtension\Dependency\Plugin\PushNotificationSenderPluginInterface>
     */
    protected function getPushNotificationSenderPlugins(): array
    {
        return [];
    }
}
