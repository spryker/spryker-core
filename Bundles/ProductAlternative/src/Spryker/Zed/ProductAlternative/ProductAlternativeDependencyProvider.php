<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeBridge;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeBridge;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductCategoryQueryContainerBridge;
use Spryker\Zed\ProductAlternative\Dependency\QueryContainer\ProductAlternativeToProductQueryContainerBridge;

class ProductAlternativeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    public const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductQueryContainer($container);
        $container = $this->addProductCategoryQueryContainer($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductAlternativeToProductQueryContainerBridge(
                $container->getLocator()->product()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return new ProductAlternativeToProductCategoryQueryContainerBridge(
                $container->getLocator()->productCategory()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductAlternativeToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductAlternativeToProductFacadeBridge(
                $container->getLocator()->product()->facade()
            );
        };

        return $container;
    }
}
