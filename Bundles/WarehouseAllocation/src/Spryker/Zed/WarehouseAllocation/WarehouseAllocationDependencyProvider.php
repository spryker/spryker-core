<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\WarehouseAllocation;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\WarehouseAllocation\WarehouseAllocationConfig getConfig()
 */
class WarehouseAllocationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_SALES_ORDER_WAREHOUSE_ALLOCATION = 'PLUGINS_SALES_ORDER_WAREHOUSE_ALLOCATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addSalesOrderWarehouseAllocationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderWarehouseAllocationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_SALES_ORDER_WAREHOUSE_ALLOCATION, function () {
            return $this->getSalesOrderWarehouseAllocationPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\WarehouseAllocationExtension\Dependency\Plugin\SalesOrderWarehouseAllocationPluginInterface>
     */
    protected function getSalesOrderWarehouseAllocationPlugins(): array
    {
        return [];
    }
}
