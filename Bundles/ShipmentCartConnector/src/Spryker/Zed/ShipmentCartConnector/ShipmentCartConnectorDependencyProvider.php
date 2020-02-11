<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToPriceFacadeBridge;
use Spryker\Zed\ShipmentCartConnector\Dependency\Facade\ShipmentCartConnectorToShipmentFacadeBridge;
use Spryker\Zed\ShipmentCartConnector\Dependency\Service\ShipmentCartConnectorToShipmentServiceBridge;

/**
 * @method \Spryker\Zed\ShipmentCartConnector\ShipmentCartConnectorConfig getConfig()
 */
class ShipmentCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SHIPMENT = 'shipment facade';
    public const FACADE_PRICE = 'price facade';

    public const SERVICE_SHIPMENT = 'SERVICE_SHIPMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addShipmentFacade($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addShipmentService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container)
    {
        $container[static::FACADE_SHIPMENT] = function (Container $container) {
            return new ShipmentCartConnectorToShipmentFacadeBridge($container->getLocator()->shipment()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container)
    {
        $container[static::FACADE_PRICE] = function (Container $container) {
            return new ShipmentCartConnectorToPriceFacadeBridge($container->getLocator()->price()->facade());
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
            return new ShipmentCartConnectorToShipmentServiceBridge($container->getLocator()->shipment()->service());
        });

        return $container;
    }
}
