<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelPageSearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelPageSearch\Dependency\Facade\ProductLabelPageSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductLabelPageSearch\Dependency\Facade\ProductLabelPageSearchToProductLabelBridge;
use Spryker\Zed\ProductLabelPageSearch\Dependency\Facade\ProductLabelPageSearchToProductPageSearchBridge;
use Spryker\Zed\ProductLabelPageSearch\Dependency\Service\ProductLabelPageSearchToUtilSanitizeServiceBridge;

class ProductLabelPageSearchDependencyProvider extends AbstractBundleDependencyProvider
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
            return new ProductLabelPageSearchToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductLabelPageSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        $container[static::FACADE_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelPageSearchToProductLabelBridge($container->getLocator()->productLabel()->facade());
        };

        $container[static::FACADE_PRODUCT_PAGE_SEARCH] = function (Container $container) {
            return new ProductLabelPageSearchToProductPageSearchBridge($container->getLocator()->productPageSearch()->facade());
        };

        return $container;
    }

}
