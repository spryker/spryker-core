<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCheckoutConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentCheckoutConnector\Dependency\Facade\ShipmentCheckoutConnectorToShipmentFacadeBridge;
use Spryker\Zed\ShipmentCheckoutConnector\Dependency\Service\ShipmentCheckoutConnectorToShipmentServiceBridge;

/**
 * @method \Spryker\Zed\ShipmentCheckoutConnector\ShipmentCheckoutConnectorConfig getConfig()
 */
class ShipmentCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addShipmentFacade($container);
        $container = $this->addShipmentService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addShipmentFacade(Container $container)
    {
        $container[static::FACADE_SHIPMENT] = function (Container $container) {
            return new ShipmentCheckoutConnectorToShipmentFacadeBridge($container->getLocator()->shipment()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentService(Container $container): Container
    {
        $container->set(static::SERVICE_SHIPMENT, function (Container $container) {
            return new ShipmentCheckoutConnectorToShipmentServiceBridge($container->getLocator()->shipment()->service());
        });

        return $container;
    }
}
