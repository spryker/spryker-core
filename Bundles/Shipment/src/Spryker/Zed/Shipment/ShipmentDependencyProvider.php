<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToMoneyBridge;
use Spryker\Zed\Shipment\Dependency\Facade\ShipmentToStoreBridge;
use Spryker\Zed\Shipment\Dependency\ShipmentToTaxBridge;

class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{
    const STORE = 'STORE';
    const PLUGINS = 'PLUGINS';
    const AVAILABILITY_PLUGINS = 'AVAILABILITY_PLUGINS';
    const PRICE_PLUGINS = 'PRICE_PLUGINS';
    const DELIVERY_TIME_PLUGINS = 'DELIVERY_TIME_PLUGINS';
    const FACADE_TAX = 'facade tax';

    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';
    const FACADE_MONEY = 'money facade';
    const METHOD_FILTER_PLUGINS = 'shipment method filter plugins';

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
        $container = $this->addStore($container);

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
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return new ShipmentToStoreBridge(Store::getInstance());
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

        $container = $this->addMethodFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMethodFilterPlugins(Container $container)
    {
        $container[static::METHOD_FILTER_PLUGINS] = function (Container $container) {
            return $this->getMethodFilterPlugins($container);
        };

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Shipment\Dependency\Plugin\ShipmentMethodFilterPluginInterface[]
     */
    protected function getMethodFilterPlugins(Container $container)
    {
        return [];
    }
}
