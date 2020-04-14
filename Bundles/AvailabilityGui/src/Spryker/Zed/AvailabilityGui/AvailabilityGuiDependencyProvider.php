<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui;

use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToLocaleBridge;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToOmsFacadeBridge;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityGuiToStockBridge;
use Spryker\Zed\AvailabilityGui\Dependency\Facade\AvailabilityToStoreFacadeBridge;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToAvailabilityQueryContainerBridge;
use Spryker\Zed\AvailabilityGui\Dependency\QueryContainer\AvailabilityGuiToProductBundleQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\AvailabilityGui\AvailabilityGuiConfig getConfig()
 */
class AvailabilityGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'locale facade';
    public const FACADE_STOCK = 'stock facade';
    public const FACADE_STORE = 'store facade';
    public const FACADE_OMS = 'oms facade';

    public const QUERY_CONTAINER_AVAILABILITY = 'availability query container';
    public const QUERY_CONTAINER_PRODUCT_BUNDLE = 'product bundle query container';

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
        $container = $this->addFacadeLocale($container);
        $container = $this->addFacadeStock($container);
        $container = $this->addQueryContainerAvailability($container);
        $container = $this->addQueryContainerProductBundle($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addStoreFacade(Container $container)
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new AvailabilityToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueryContainerProductBundle(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_BUNDLE] = function (Container $container) {
            return new AvailabilityGuiToProductBundleQueryContainerBridge($container->getLocator()->productBundle()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueryContainerAvailability(Container $container)
    {
        $container[static::QUERY_CONTAINER_AVAILABILITY] = function (Container $container) {
            return new AvailabilityGuiToAvailabilityQueryContainerBridge($container->getLocator()->availability()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeStock(Container $container)
    {
        $container[static::FACADE_STOCK] = function (Container $container) {
            return new AvailabilityGuiToStockBridge($container->getLocator()->stock()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeLocale(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new AvailabilityGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container)
    {
        $container[static::FACADE_OMS] = function (Container $container) {
            return new AvailabilityGuiToOmsFacadeBridge($container->getLocator()->oms()->facade());
        };

        return $container;
    }
}
