<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales;

use Spryker\Zed\Application\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToOmsBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSalesAggregatorBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToSequenceNumberBridge;
use Spryker\Zed\Sales\Dependency\Facade\SalesToUserBridge;

class SalesDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_COUNTRY = 'FACADE_COUNTRY';
    const FACADE_OMS = 'FACADE_OMS';
    const FACADE_SEQUENCE_NUMBER = 'FACADE_SEQUENCE_NUMBER';
    const FACADE_USER = 'FACADE_USER';
    const FACADE_SALES_AGGREGATOR = 'FACADE_SALES_AGGREGATOR';
    const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @deprecated Will be removed in the next major version.
     */
    const FACADE_LOCALE = 'LOCALE_FACADE';

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

        $container[self::FACADE_SALES_AGGREGATOR] = function (Container $container) {
            return new SalesToSalesAggregatorBridge($container->getLocator()->salesAggregator()->facade());
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

        $container[self::SERVICE_DATE_FORMATTER] = function () {
            return (new Pimple())->getApplication()['dateFormatter'];
        };

        $container[self::FACADE_COUNTRY] = function (Container $container) {
            return new SalesToCountryBridge($container->getLocator()->country()->facade());
        };

        return $container;
    }

}
