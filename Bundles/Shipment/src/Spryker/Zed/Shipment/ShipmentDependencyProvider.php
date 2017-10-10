<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToCurrencyBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreBridge as ShipmentToStoreFacadeBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;

class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGINS = 'PLUGINS';
    const AVAILABILITY_PLUGINS = 'AVAILABILITY_PLUGINS';
    const PRICE_PLUGINS = 'PRICE_PLUGINS';
    const DELIVERY_TIME_PLUGINS = 'DELIVERY_TIME_PLUGINS';

    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    const FACADE_MONEY = 'FACADE_MONEY';
    const FACADE_CURRENCY = 'FACADE_CURRENCY';
    const FACADE_STORE = 'FACADE_STORE';
    const FACADE_TAX = 'FACADE_TAX';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::PLUGINS] = function (Container $container) {
            return [
                static::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                static::PRICE_PLUGINS => $this->getPricePlugins($container),
                static::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
            ];
        };

        $container = $this->addMoneyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCurrencyFacade($container);

        $container[static::FACADE_TAX] = function (Container $container) {
            return new ShipmentToTaxBridge($container->getLocator()->tax()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new ShipmentToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new ShipmentToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new ShipmentToCurrencyBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::PLUGINS] = function (Container $container) {
            return [
                static::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                static::PRICE_PLUGINS => $this->getPricePlugins($container),
                static::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
            ];
        };

        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        $container[static::FACADE_TAX] = function (Container $container) {
            return new ShipmentToTaxBridge($container->getLocator()->tax()->facade());
        };

        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getAvailabilityPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getPricePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function getDeliveryTimePlugins(Container $container)
    {
        return [];
    }

}
