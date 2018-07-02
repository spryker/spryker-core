<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesStatistics;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;

class SalesStatisticsDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_SALES_ORDER_QUERY = 'SALES_ORDER_QUERY';
    public const PROPEL_SALES_ORDER_ITEM_QUERY = 'SALES_ORDER_ITEM_QUERY';
    public const RENDERER = 'RENDERER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addSalesOrderQuery($container);
        $container = $this->addSalesOrderItemQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addRenderer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderQuery(Container $container): Container
    {
        $container[static::PROPEL_SALES_ORDER_QUERY] = function (Container $container) {
            return SpySalesOrderQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderItemQuery(Container $container): Container
    {
        $container[static::PROPEL_SALES_ORDER_ITEM_QUERY] = function (Container $container) {
            return SpySalesOrderItemQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRenderer(Container $container): Container
    {
        $container[static::RENDERER] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication()['twig'];
        };

        return $container;
    }
}
