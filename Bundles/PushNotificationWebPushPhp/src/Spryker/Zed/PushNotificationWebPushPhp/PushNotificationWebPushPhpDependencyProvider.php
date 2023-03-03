<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\WebPush;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToSubscriptionAdapter;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\External\PushNotificationWebPushPhpToWebPushAdapter;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Facade\PushNotificationWebPushPhpToPushNotificationFacadeBridge;
use Spryker\Zed\PushNotificationWebPushPhp\Dependency\Service\PushNotificationWebPushPhpToUtilEncodingServiceBridge;

/**
 * @method \Spryker\Zed\PushNotificationWebPushPhp\PushNotificationWebPushPhpConfig getConfig()
 */
class PushNotificationWebPushPhpDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PUSH_NOTIFICATION = 'FACADE_PUSH_NOTIFICATION';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const WEB_PUSH_NOTIFICATOR = 'WEB_PUSH_NOTIFICATOR';

    /**
     * @var string
     */
    public const WEB_PUSH_SUBSCRIPTION = 'WEB_PUSH_SUBSCRIPTION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addUtilEncodingService($container);
        $container = $this->addPushNotificationFacade($container);
        $container = $this->addWebPushNotificator($container);
        $container = $this->addWebPushSubscription($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPushNotificationFacade(Container $container): Container
    {
        $container->set(static::FACADE_PUSH_NOTIFICATION, function (Container $container) {
            return new PushNotificationWebPushPhpToPushNotificationFacadeBridge(
                $container->getLocator()->pushNotification()->facade(),
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
            return new PushNotificationWebPushPhpToUtilEncodingServiceBridge(
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
    protected function addWebPushNotificator(Container $container): Container
    {
        $container->set(static::WEB_PUSH_NOTIFICATOR, function () {
            return new PushNotificationWebPushPhpToWebPushAdapter(
                $this->getWebPush(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addWebPushSubscription(Container $container): Container
    {
        $container->set(static::WEB_PUSH_SUBSCRIPTION, function () {
            return new PushNotificationWebPushPhpToSubscriptionAdapter();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\PushNotificationWebPushPhp\Business\WebPush\WebPush
     */
    protected function getWebPush(): WebPush
    {
        return new WebPush($this->getConfig()->getVAPIDAuthCredentials());
    }
}
