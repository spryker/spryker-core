<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductLabelBridge;
use Spryker\Zed\ProductLabelSearch\Dependency\Facade\ProductLabelSearchToProductPageSearchBridge;
use Spryker\Zed\ProductLabelSearch\Dependency\Service\ProductLabelSearchToUtilSanitizeServiceBridge;

class ProductLabelSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    const FACADE_PRODUCT_PAGE_SEARCH = 'FACADE_PRODUCT_PAGE_SEARCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new ProductLabelSearchToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductLabelSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        $container[static::FACADE_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelSearchToProductLabelBridge($container->getLocator()->productLabel()->facade());
        };

        $container[static::FACADE_PRODUCT_PAGE_SEARCH] = function (Container $container) {
            return new ProductLabelSearchToProductPageSearchBridge($container->getLocator()->productPageSearch()->facade());
        };

        return $container;
    }
}
