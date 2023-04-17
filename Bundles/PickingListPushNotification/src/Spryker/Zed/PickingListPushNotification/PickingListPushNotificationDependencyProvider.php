<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToPushNotificationFacadeBridge;
use Spryker\Zed\PickingListPushNotification\Dependency\Facade\PickingListPushNotificationToWarehouseUserFacadeBridge;

/**
 * @method \Spryker\Zed\PickingListPushNotification\PickingListPushNotificationConfig getConfig()
 */
class PickingListPushNotificationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_WAREHOUSE_USER = 'FACADE_WAREHOUSE_USER';

    /**
     * @var string
     */
    public const FACADE_PUSH_NOTIFICATION = 'FACADE_PUSH_NOTIFICATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addWarehouseUserFacade($container);
        $container = $this->addPushNotificationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addWarehouseUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_WAREHOUSE_USER, function (Container $container) {
            return new PickingListPushNotificationToWarehouseUserFacadeBridge(
                $container->getLocator()->warehouseUser()->facade(),
            );
        });

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
            return new PickingListPushNotificationToPushNotificationFacadeBridge(
                $container->getLocator()->pushNotification()->facade(),
            );
        });

        return $container;
    }
}
