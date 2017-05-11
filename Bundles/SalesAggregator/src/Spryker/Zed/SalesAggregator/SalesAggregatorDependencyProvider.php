<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesAggregator\Communication\Plugin\OrderAmountAggregator\ExpenseTotalAggregatorPlugin;
use Spryker\Zed\SalesAggregator\Communication\Plugin\OrderAmountAggregator\GrandTotalAggregatorPlugin;
use Spryker\Zed\SalesAggregator\Communication\Plugin\OrderAmountAggregator\ItemGrossPriceAggregatorPlugin;
use Spryker\Zed\SalesAggregator\Communication\Plugin\OrderAmountAggregator\ItemTaxAmountAggregatorPlugin;
use Spryker\Zed\SalesAggregator\Communication\Plugin\OrderAmountAggregator\OrderTaxAmountAggregatorPlugin;
use Spryker\Zed\SalesAggregator\Communication\Plugin\OrderAmountAggregator\SubtotalOrderAggregatorPlugin;
use Spryker\Zed\SalesAggregator\Dependency\Facade\SalesAggregatorToTaxBridge;

class SalesAggregatorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_TAX = 'FACADE_TAX';
    const QUERY_CONTAINER_SALES = 'SALES_QUERY_CONTAINER';
    const QUERY_CONTAINER_DISCOUNTS = 'QUERY_CONTAINER_DISCOUNTS';

    const QUERY_CONTAINER_PRODUCT_OPTIONS = 'PRODUCT_OPTION_QUERY_CONTAINER';
    const PLUGINS_ORDER_AMOUNT_AGGREGATION = 'PLUGINS_ORDER_AMOUNT_AGGREGATION';
    const PLUGINS_ITEM_AMOUNT_AGGREGATION = 'PLUGINS_ITEM_AMOUNT_AGGREGATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_TAX] = function (Container $container) {
            return new SalesAggregatorToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::PLUGINS_ORDER_AMOUNT_AGGREGATION] = function (Container $container) {
            return $this->getOrderAmountAggregationPlugins($container);
        };

        $container[self::PLUGINS_ITEM_AMOUNT_AGGREGATION] = function (Container $container) {
            return $this->getItemAmountAggregationPlugins($container);
        };

        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_PRODUCT_OPTIONS] = function (Container $container) {
            return $container->getLocator()->productOption()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_DISCOUNTS] = function (Container $container) {
            return $container->getLocator()->discount()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getPaymentLogPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getItemAmountAggregationPlugins(Container $container)
    {
        return [
            //aggregate sum* fields, so that amount with quantity is available.
            new ItemGrossPriceAggregatorPlugin(),

            //Add tax amount for each item
            new ItemTaxAmountAggregatorPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\SalesAggregator\Dependency\Plugin\OrderTotalsAggregatePluginInterface[]
     */
    protected function getOrderAmountAggregationPlugins(Container $container)
    {
        return [
            //order level expense total amount
            new ExpenseTotalAggregatorPlugin(),

            //SubTotal sum of all items.
            new SubtotalOrderAggregatorPlugin(),

            //Aggregate Grand total amount, subtotal + expenses
            new GrandTotalAggregatorPlugin(),

            //add tax amount for order level
            new OrderTaxAmountAggregatorPlugin(),
        ];
    }

}
