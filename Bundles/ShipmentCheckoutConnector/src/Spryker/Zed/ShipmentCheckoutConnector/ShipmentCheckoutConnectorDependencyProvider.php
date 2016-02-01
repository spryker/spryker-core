<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ShipmentCheckoutConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentBridge;

class ShipmentCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    const QUERY_CONTAINER_SHIPMENT = 'QUERY_CONTAINER_SHIPMENT';

    const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_SHIPMENT] = function (Container $container) {
            return new ShipmentCheckoutConnectorToShipmentBridge($container->getLocator()->shipment()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_SALES] = function (Container $container) {
            return $container->getLocator()->sales()->queryContainer();
        };

        $container[self::QUERY_CONTAINER_SHIPMENT] = function (Container $container) {
            return $container->getLocator()->shipment()->queryContainer();
        };

        return $container;
    }

}
