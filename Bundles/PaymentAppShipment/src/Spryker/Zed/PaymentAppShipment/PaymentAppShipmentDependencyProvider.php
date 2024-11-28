<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentAppShipment;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PaymentAppShipment\Dependency\Facade\PaymentAppShipmentToShipmentFacadeBridge;

/**
 * @method \Spryker\Zed\PaymentAppShipment\PaymentAppShipmentConfig getConfig()
 */
class PaymentAppShipmentDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addShipmentFacade($container);

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
            return new PaymentAppShipmentToShipmentFacadeBridge($container->getLocator()->shipment()->facade());
        });

        return $container;
    }
}
