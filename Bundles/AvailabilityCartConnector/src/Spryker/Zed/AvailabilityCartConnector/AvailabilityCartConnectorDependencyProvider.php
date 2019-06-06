<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCartConnector;

use Spryker\Zed\AvailabilityCartConnector\Dependency\Facade\AvailabilityCartConnectorToAvailabilityBridge;
use Spryker\Zed\AvailabilityCartConnector\Dependency\Service\AvailabilityCartConnectorToUtilQuantityServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_AVAILABILITY = 'availability facade';

    public const SERVICE_UTIL_QUANTITY = 'SERVICE_UTIL_QUANTITY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addAvailabilityFacade($container);
        $container = $this->addUtilQuantityService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilQuantityService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_QUANTITY] = function (Container $container) {
            return new AvailabilityCartConnectorToUtilQuantityServiceBridge(
                $container->getLocator()->utilQuantity()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAvailabilityFacade(Container $container): Container
    {
        $container[static::FACADE_AVAILABILITY] = function (Container $container) {
            return new AvailabilityCartConnectorToAvailabilityBridge($container->getLocator()->availability()->facade());
        };

        return $container;
    }
}
