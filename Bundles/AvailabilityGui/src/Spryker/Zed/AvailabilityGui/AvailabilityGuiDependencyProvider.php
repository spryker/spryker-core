<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui;

use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToAvailabilityBridge;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToLocaleBridge;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockBridge;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerBridge;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AvailabilityGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_LOCALE = 'locale facade';
    const FACADE_STOCK = 'stock facade';
    const FACADE_AVAILABILITY = 'availability facade';

    const QUERY_CONTAINER_AVAILABILITY = 'availability query container';
    const QUERY_CONTAINER_PRODUCT_BUNDLE = 'product bundle query container';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new AvailabilityGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[static::FACADE_STOCK] = function (Container $container) {
            return new AvailabilityGuiToStockBridge($container->getLocator()->stock()->facade());
        };

        $container[static::FACADE_AVAILABILITY] = function (Container $container) {
            return new AvailabilityGuiToAvailabilityBridge($container->getLocator()->availability()->facade());
        };

        $container[static::QUERY_CONTAINER_AVAILABILITY] = function (Container $container) {
            return new AvailabilityGuiToAvailabilityQueryContainerBridge($container->getLocator()->availability()->queryContainer());
        };

        $container[static::QUERY_CONTAINER_PRODUCT_BUNDLE] = function (Container $container) {
            return new AvailabilityGuiToProductBundleQueryContainerBridge($container->getLocator()->productBundle()->queryContainer());
        };

        return $container;
    }
}
