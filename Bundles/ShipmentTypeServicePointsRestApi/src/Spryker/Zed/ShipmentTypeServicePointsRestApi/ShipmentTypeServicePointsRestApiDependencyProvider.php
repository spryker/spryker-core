<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToCustomerFacadeBridge;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToShipmentFacadeBridge;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePointsRestApi\ShipmentTypeServicePointsRestApiConfig getConfig()
 */
class ShipmentTypeServicePointsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addShipmentFacade($container);
        $container = $this->addCustomerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
            return new ShipmentTypeServicePointsRestApiToShipmentFacadeBridge(
                $container->getLocator()->shipment()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new ShipmentTypeServicePointsRestApiToCustomerFacadeBridge(
                $container->getLocator()->customer()->facade(),
            );
        });

        return $container;
    }
}
