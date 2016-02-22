<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityCheckoutConnector;

use Spryker\Zed\AvailabilityCheckoutConnector\Dependency\Facade\AvailabilityCheckoutConnectorToAvailabilityBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_AVAILABILITY = 'availability facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_AVAILABILITY] = function (Container $container) {
            return new AvailabilityCheckoutConnectorToAvailabilityBridge(
                $container->getLocator()->availability()->facade()
            );
        };

        return $container;
    }

}
