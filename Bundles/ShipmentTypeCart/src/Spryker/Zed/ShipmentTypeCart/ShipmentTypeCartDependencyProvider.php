<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ShipmentTypeCart\Dependency\Facade\ShipmentTypeCartToShipmentTypeFacadeBridge;

/**
 * @method \Spryker\Zed\ShipmentTypeCart\ShipmentTypeCartConfig getConfig()
 */
class ShipmentTypeCartDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SHIPMENT_TYPE = 'FACADE_SHIPMENT_TYPE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addShipmentTypeFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentTypeFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT_TYPE, function (Container $container) {
            return new ShipmentTypeCartToShipmentTypeFacadeBridge(
                $container->getLocator()->shipmentType()->facade(),
            );
        });

        return $container;
    }
}
