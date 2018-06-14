<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToProductSearchFacadeBridge;

class ProductSearchConfigStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT_SEARCH = 'FACADE_PRODUCT_SEARCH';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const CONFIG_PRODUCT_SEARCH = 'CONFIG_PRODUCT_SEARCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductSearchConfigStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::CONFIG_PRODUCT_SEARCH] = function (Container $container) {
            return new ProductSearchConfig();
        };

        $container[static::FACADE_PRODUCT_SEARCH] = function (Container $container) {
            return new ProductSearchConfigStorageToProductSearchFacadeBridge($container->getLocator()->productSearch()->facade());
        };

        return $container;
    }
}
