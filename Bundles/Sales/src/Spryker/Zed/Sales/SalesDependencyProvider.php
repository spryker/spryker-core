<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToUserBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;

class SalesDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COUNTRY = 'FACADE_COUNTRY';
    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_LOCALE = 'LOCALE_FACADE';
    const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';
    const FACADE_USER = 'FACADE_USER';
    const FACADE_SALES_AGGREGATOR = 'FACADE_SALES_AGGREGATOR';

    const PLUGINS_PAYMENT_LOGS = 'PLUGINS_PAYMENT_LOGS';

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

        $container[self::PLUGINS_PAYMENT_LOGS] = function (Container $container) {
            return $this->getPaymentLogPlugins($container);
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

        $container[self::FACADE_USER] = function (Container $container) {
            return new SalesToUserBridge($container->getLocator()->user()->facade());
        };

        $container[self::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new SalesToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
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

}
