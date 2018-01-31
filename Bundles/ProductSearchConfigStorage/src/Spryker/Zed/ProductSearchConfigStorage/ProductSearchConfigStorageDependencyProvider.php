<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage;

use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductSearchConfigStorage\Dependency\Facade\ProductSearchConfigStorageToProductSearchToProductSearchBridge;
use Spryker\Zed\ProductSearchConfigStorage\Dependency\Service\ProductSearchConfigStorageToUtilSanitizeServiceBridge;

class ProductSearchConfigStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT_SEARCH = 'FACADE_PRODUCT_SEARCH';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const CONFIG_PRODUCT_SEARCH = 'CONFIG_PRODUCT_SEARCH';
    const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new ProductSearchConfigStorageToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductSearchConfigStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        $container[static::STORE] = function (Container $container) {
            return Store::getInstance();
        };

        $container[static::CONFIG_PRODUCT_SEARCH] = function (Container $container) {
            return new ProductSearchConfig();
        };

        $container[static::FACADE_PRODUCT_SEARCH] = function (Container $container) {
            return new ProductSearchConfigStorageToProductSearchToProductSearchBridge($container->getLocator()->productSearch()->facade());
        };

        return $container;
    }
}
