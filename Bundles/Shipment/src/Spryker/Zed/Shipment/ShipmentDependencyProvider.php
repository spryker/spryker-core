<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Shipment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{

    const AVAILABILITY_PLUGINS = 'AVAILABILITY_PLUGINS';
    const PRICE_CALCULATION_PLUGINS = 'PRICE_CALCULATION_PLUGINS';
    const TAX_CALCULATION_PLUGINS = 'TAX_CALCULATION_PLUGINS';
    const DELIVERY_TIME_PLUGINS = 'DELIVERY_TIME_PLUGINS';
    const PLUGINS = 'PLUGINS';

    const QUERY_CONTAINER_TAX = 'QUERY_CONTAINER_TAX';

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::PLUGINS] = function (Container $container) {

            return [
                self::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                self::PRICE_CALCULATION_PLUGINS => $this->getPriceCalculationPlugins($container),
                self::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
            ];
        };

        $container[static::QUERY_CONTAINER_TAX] = function (Container $container) {
            return $container->getLocator()->tax()->queryContainer();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::PLUGINS] = function (Container $container) {

            return [
                self::AVAILABILITY_PLUGINS => $this->getAvailabilityPlugins($container),
                self::PRICE_CALCULATION_PLUGINS => $this->getPriceCalculationPlugins($container),
                self::DELIVERY_TIME_PLUGINS => $this->getDeliveryTimePlugins($container),
                self::TAX_CALCULATION_PLUGINS => $this->getTaxCalculationPlugins($container),
            ];
        };

        parent::provideBusinessLayerDependencies($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected function getAvailabilityPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected function getPriceCalculationPlugins(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected function getDeliveryTimePlugins(Container $container)
    {
        return [];
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected function getTaxCalculationPlugins(Container $container)
    {
        return [];
    }

}
