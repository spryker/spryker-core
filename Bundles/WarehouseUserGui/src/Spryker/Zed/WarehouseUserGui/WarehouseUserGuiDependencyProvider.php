<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseUserGui;

use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\WarehouseUser\Persistence\SpyWarehouseUserAssignmentQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\WarehouseUserGui\Dependency\Facade\WarehouseUserGuiToUserFacadeBridge;
use Spryker\Zed\WarehouseUserGui\Dependency\Facade\WarehouseUserGuiToWarehouseUserFacadeBridge;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilEncodingServiceBridge;
use Spryker\Zed\WarehouseUserGui\Dependency\Service\WarehouseUserGuiToUtilSanitizeServiceBridge;

/**
 * @method \Spryker\Zed\WarehouseUserGui\WarehouseUserGuiConfig getConfig()
 */
class WarehouseUserGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @var string
     */
    public const FACADE_WAREHOUSE_USER = 'FACADE_WAREHOUSE_USER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const PROPEL_QUERY_STOCK = 'PROPEL_QUERY_STOCK';

    /**
     * @var string
     */
    public const PROPEL_QUERY_WAREHOUSE_USER_ASSIGNMENT = 'PROPEL_QUERY_WAREHOUSE_USER_ASSIGNMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addUserFacade($container);
        $container = $this->addWarehouseUserFacade($container);
        $container = $this->addUtilSanitizeService($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addStockPropelQuery($container);
        $container = $this->addWarehouseUserAssignmentPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new WarehouseUserGuiToUserFacadeBridge($container->getLocator()->user()->facade());
        });

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
            return new WarehouseUserGuiToWarehouseUserFacadeBridge($container->getLocator()->warehouseUser()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new WarehouseUserGuiToUtilSanitizeServiceBridge(
                $container->getLocator()->utilSanitize()->service(),
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
            return new WarehouseUserGuiToUtilEncodingServiceBridge(
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
    protected function addStockPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_STOCK, $container->factory(function () {
            return SpyStockQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addWarehouseUserAssignmentPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_WAREHOUSE_USER_ASSIGNMENT, $container->factory(function () {
            return SpyWarehouseUserAssignmentQuery::create();
        }));

        return $container;
    }
}
