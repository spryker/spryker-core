<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\Shipment\Dependency\Service\ShipmentToCustomerServiceBridge;

/**
 * @method \Spryker\Service\Shipment\ShipmentConfig getConfig()
 */
class ShipmentDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_CUSTOMER = 'SERVICE_CUSTOMER';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addCustomerService($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addCustomerService(Container $container): Container
    {
        $container->set(static::SERVICE_CUSTOMER, function (Container $container) {
            return new ShipmentToCustomerServiceBridge($container->getLocator()->customer()->service());
        });

        return $container;
    }
}
