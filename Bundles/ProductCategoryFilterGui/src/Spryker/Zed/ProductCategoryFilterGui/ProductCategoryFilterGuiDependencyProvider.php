<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToLocaleBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\QueryContainer\ProductCategoryFilterGuiToCategoryBridge as ProductCategoryFilterGuiToCategoryQueryContainerBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToCategoryBridge as ProductCategoryFilterGuiToCategoryFacadeBridge;

class ProductCategoryFilterGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT_CATEGORY_FILTER = 'FACADE_PRODUCT_CATEGORY_FILTER';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_CATEGORY = 'FACADE_CATEGORY';
    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addProductCategoryFilterFacade($container);
        $this->addLocaleFacade($container);
        $this->addCategoryFacade($container);
        $this->addCategoryQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $this->addCategoryQueryContainer($container);

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return new ProductCategoryFilterGuiToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductCategoryFilterGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCategoryFacade(Container $container)
    {
        $container[static::FACADE_CATEGORY] = function (Container $container) {
            return new ProductCategoryFilterGuiToCategoryFacadeBridge($container->getLocator()->category()->facade());
        };
    }
}
