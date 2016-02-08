<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Communication\Plugin\OrderAmountAggregator\ExpenseTotalAggregatorPlugin;
use Spryker\Zed\Sales\Communication\Plugin\OrderAmountAggregator\GrandTotalAggregatorPlugin;
use Spryker\Zed\Sales\Communication\Plugin\OrderAmountAggregator\ItemGrossPriceAggregatorPlugin;
use Spryker\Zed\Sales\Communication\Plugin\OrderAmountAggregator\SubtotalOrderAggregatorPlugin;
use Spryker\Zed\Sales\Dependency\Facade\SalesToTaxBridge;
use Spryker\Zed\Sales\Dependency\Plugin\OrderTotalsAggregatePluginInterface;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToRefundBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\Communication\Plugin\OrderAmountAggregator\ItemTaxAmountAggregatorPlugin;
use Spryker\Zed\Sales\Communication\Plugin\OrderAmountAggregator\OrderTaxAmountAggregatorPlugin;

class SalesDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COUNTRY = 'FACADE_COUNTRY';
    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_REFUND = 'FACADE_REFUND';
    const FACADE_LOCALE = 'LOCALE_FACADE';
    const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';
    const FACADE_TAX = 'FACADE_TAX';

    const PLUGINS_PAYMENT_LOGS = 'PLUGINS_PAYMENT_LOGS';
    const PLUGINS_ORDER_AMOUNT_AGGREGATION =  'PLUGINS_ORDER_AMOUNT_AGGREGATION';
    const PLUGINS_ITEM_AMOUNT_AGGREGATION =  'PLUGINS_ITEM_AMOUNT_AGGREGATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_SEQUENCE_NUMBER] = function (Container $container) {
            return new SalesToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        };

        $container[self::FACADE_COUNTRY] = function (Container $container) {
            return new SalesToCountryBridge($container->getLocator()->country()->facade());
        };

        $container[self::FACADE_OMS] = function (Container $container) {
            return new SalesToOmsBridge($container->getLocator()->oms()->facade());
        };

        $container[self::FACADE_REFUND] = function (Container $container) {
            return new SalesToRefundBridge($container->getLocator()->refund()->facade());
        };

        $container[self::FACADE_TAX] = function (Container $container) {
            return new SalesToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container[self::PLUGINS_PAYMENT_LOGS] = function (Container $container) {
            return $this->getPaymentLogPlugins($container);
        };

        $container[self::PLUGINS_ORDER_AMOUNT_AGGREGATION] = function (Container $container) {
            return $this->getOrderAmountAggregationPlugins($container);
        };

        $container[self::PLUGINS_ITEM_AMOUNT_AGGREGATION] = function (Container $container) {
            return $this->getItemAmountAggregationPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_OMS] = function (Container $container) {
            return new SalesToOmsBridge($container->getLocator()->oms()->facade());
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
     * @param Container $container
     *
     * @return array|OrderTotalsAggregatePluginInterface[]
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
     * @param Container $container
     *
     * @return array|OrderTotalsAggregatePluginInterface[]
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
