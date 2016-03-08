<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{

    const PLUGINS = 'PLUGINS';
    const AVAILABILITY_PLUGINS = 'AVAILABILITY_PLUGINS';
    const PRICE_PLUGINS = 'PRICE_PLUGINS';
    const DELIVERY_TIME_PLUGINS = 'DELIVERY_TIME_PLUGINS';

    const QUERY_CONTAINER_TAX = 'QUERY_CONTAINER_TAX';
    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::PLUGINS] = function (Container $container) {

            return [
                self::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                self::PRICE_PLUGINS => $this->getPricePlugins($container),
                self::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
            ];
        };

        $container[static::QUERY_CONTAINER_TAX] = function (Container $container) {
            return $container->getLocator()->tax()->queryContainer();
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
        $container[self::PLUGINS] = function (Container $container) {

            return [
                self::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                self::PRICE_PLUGINS => $this->getPricePlugins($container),
                self::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
            ];
        };

        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        parent::provideBusinessLayerDependencies($container);

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
