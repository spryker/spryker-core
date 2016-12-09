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
use Spryker\Zed\Sales\Dependency\Facade\SalesToMoneyBridge;
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
    const FACADE_MONEY = 'money facade';

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
        $container = $this->addSequenceNumberFacade($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addSalesAggregatorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addOmsFacade($container);
        $container = $this->addUserFacade($container);
        $container = $this->addSalesAggregatorFacade($container);
        $container = $this->addDateTimeFormatter($container);
        $container = $this->addCountryFacade($container);
        $container = $this->addMoneyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyPlugin(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new SalesToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container)
    {
        $container[self::FACADE_OMS] = function (Container $container) {
            return new SalesToOmsBridge($container->getLocator()->oms()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCountryFacade(Container $container)
    {
        $container[self::FACADE_COUNTRY] = function (Container $container) {
            return new SalesToCountryBridge($container->getLocator()->country()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSequenceNumberFacade(Container $container)
    {
        $container[self::FACADE_SEQUENCE_NUMBER] = function (Container $container) {
            return new SalesToSequenceNumberBridge($container->getLocator()->sequenceNumber()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesAggregatorFacade(Container $container)
    {
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
    protected function addUserFacade(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return new SalesToUserBridge($container->getLocator()->user()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDateTimeFormatter(Container $container)
    {
        $container[self::SERVICE_DATE_FORMATTER] = function () {
            return (new Pimple())->getApplication()['dateFormatter'];
        };

        return $container;
    }

}
