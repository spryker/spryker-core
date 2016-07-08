<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability;

use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToOmsBridge;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockBridge;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToTouchBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_OMS = 'oms facade';
    const FACADE_STOCK = 'stock facade';
    const FACADE_TOUCH = 'touch facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_OMS] = function (Container $container) {
            return new AvailabilityToOmsBridge($container->getLocator()->oms()->facade());
        };

        $container[self::FACADE_STOCK] = function (Container $container) {
            return new AvailabilityToStockBridge($container->getLocator()->stock()->facade());
        };

        $container[self::FACADE_TOUCH] = function (Container $container) {
            return new AvailabilityToTouchBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }

}
