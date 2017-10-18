<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterBridge;

class ProductCategoryFilterGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT_CATEGORY_FILTER = 'FACADE_PRODUCT_CATEGORY_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addProductCategoryFilterFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryFilterFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_CATEGORY_FILTER] = function (Container $container) {
            return new ProductCategoryFilterGuiToProductCategoryFilterBridge($container->getLocator()->productCategoryFilter()->facade());
        };

        return $container;
    }
}
