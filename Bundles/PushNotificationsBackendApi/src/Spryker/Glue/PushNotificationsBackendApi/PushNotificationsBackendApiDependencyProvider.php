<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PushNotificationsBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Client\PushNotificationsBackendApiToGlossaryStorageClientBridge;
use Spryker\Glue\PushNotificationsBackendApi\Dependency\Facade\PushNotificationsBackendApiToPushNotificationFacadeBridge;

/**
 * @method \Spryker\Glue\PushNotificationsBackendApi\PushNotificationsBackendApiConfig getConfig()
 */
class PushNotificationsBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PUSH_NOTIFICATION = 'FACADE_PUSH_NOTIFICATION';

    /**
     * @var string
     */
    public const CLIENT_GLOSSARY_STORAGE = 'CLIENT_GLOSSARY_STORAGE';

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    public function provideBackendDependencies(Container $container): Container
    {
        $container = $this->addPushNotificationFacade($container);
        $container = $this->addGlossaryStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addPushNotificationFacade(Container $container): Container
    {
        $container->set(static::FACADE_PUSH_NOTIFICATION, function (Container $container) {
            return new PushNotificationsBackendApiToPushNotificationFacadeBridge(
                $container->getLocator()->pushNotification()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Backend\Container $container
     *
     * @return \Spryker\Glue\Kernel\Backend\Container
     */
    protected function addGlossaryStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_GLOSSARY_STORAGE, function (Container $container) {
            return new PushNotificationsBackendApiToGlossaryStorageClientBridge(
                $container->getLocator()->glossaryStorage()->client(),
            );
        });

        return $container;
    }
}
