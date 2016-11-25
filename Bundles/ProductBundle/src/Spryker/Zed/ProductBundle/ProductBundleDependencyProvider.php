<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductBridge;

class ProductBundleDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT = 'product facade';
    const FACADE_PRICE = 'price facade';
    const FACADE_LOCALE = 'locale facade';
    const FACADE_AVAILABILITY = 'facade availability';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new ProductBundleToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_PRICE] = function (Container $container) {
            return new ProductBundleToPriceBridge($container->getLocator()->price()->facade());
        };

        $container[self::FACADE_LOCALE] = function (Container $container) {
            return new ProductBundleToLocaleBridge($container->getLocator()->locale()->facade());
        };

        $container[self::FACADE_AVAILABILITY] = function(Container $container) {
            return new ProductBundleToAvailabilityBridge($container->getLocator()->availability()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
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
        return $container;
    }

}
