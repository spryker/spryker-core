<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToOmsFacadeBridge;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeBridge;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeBridge;
use Spryker\Zed\SalesReturn\Dependency\Service\SalesReturnToUtilDateTimeServiceBridge;

/**
 * @method \Spryker\Zed\SalesReturn\SalesReturnConfig getConfig()
 */
class SalesReturnDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_SALES = 'FACADE_SALES';
    public const FACADE_OMS = 'FACADE_OMS';

    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    public const PROPEL_QUERY_SALES_ORDER_ITEM = 'PROPEL_QUERY_SALES_ORDER_ITEM';
    public const PLUGINS_RETURN_PRE_CREATE = 'PLUGINS_RETURN_PRE_CREATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addStoreFacade($container);
        $container = $this->addSalesFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addReturnPreCreatePlugins($container);

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

        $container = $this->addSalesOrderItemPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new SalesReturnToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesFacade(Container $container): Container
    {
        $container->set(static::FACADE_SALES, function (Container $container) {
            return new SalesReturnToSalesFacadeBridge($container->getLocator()->sales()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new SalesReturnToOmsFacadeBridge($container->getLocator()->oms()->facade());
        });

        return $container;
    }

    /**
     * @module Sales
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderItemPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER_ITEM, $container->factory(function () {
            return SpySalesOrderItemQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return new SalesReturnToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addReturnPreCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_RETURN_PRE_CREATE, function (Container $container) {
            return $this->getReturnPreCreatePlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPreCreatePluginInterface[]
     */
    protected function getReturnPreCreatePlugins(): array
    {
        return [];
    }
}
