<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryFilterGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Client\ProductCategoryFilterGuiToCatalogClientBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Client\ProductCategoryFilterGuiToProductCategoryFilterClientBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToCategoryFacadeBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductCategoryFilterFacadeBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductFacadeBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade\ProductCategoryFilterGuiToProductSearchFacadeBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\QueryContainer\ProductCategoryFilterGuiToCategoryQueryContainerBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\QueryContainer\ProductCategoryFilterGuiToProductCategoryQueryContainerBridge;
use Spryker\Zed\ProductCategoryFilterGui\Dependency\Service\ProductCategoryFilterGuiToUtilEncodingServiceBridge;

class ProductCategoryFilterGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_PRODUCT_CATEGORY_FILTER = 'FACADE_PRODUCT_CATEGORY_FILTER';
    const FACADE_LOCALE = 'FACADE_LOCALE';
    const FACADE_CATEGORY = 'FACADE_CATEGORY';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_PRODUCT_SEARCH = 'FACADE_PRODUCT_SEARCH';
    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';
    const CLIENT_CATALOG = 'CLIENT_CATALOG';
    const CLIENT_PRODUCT_CATEGORY_FILTER = 'CLIENT_PRODUCT_CATEGORY_FILTER';
    const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

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
        $this->addProductSearchFacade($container);
        $this->addProductFacade($container);
        $this->addCategoryQueryContainer($container);
        $this->addCatalogClient($container);
        $this->addProductCategoryFilterClient($container);
        $this->addUtilEncodingService($container);

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
        $this->addProductCategoryQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductCategoryFilterGuiToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryFilterFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_CATEGORY_FILTER] = function (Container $container) {
            return new ProductCategoryFilterGuiToProductCategoryFilterFacadeBridge($container->getLocator()->productCategoryFilter()->facade());
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
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductCategoryQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return new ProductCategoryFilterGuiToProductCategoryQueryContainerBridge($container->getLocator()->productCategory()->queryContainer());
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
            return new ProductCategoryFilterGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductSearchFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_SEARCH] = function (Container $container) {
            return new ProductCategoryFilterGuiToProductSearchFacadeBridge($container->getLocator()->productSearch()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductCategoryFilterGuiToProductFacadeBridge($container->getLocator()->product()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCatalogClient(Container $container)
    {
        $container[static::CLIENT_CATALOG] = function (Container $container) {
            return new ProductCategoryFilterGuiToCatalogClientBridge($container->getLocator()->catalog()->client());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductCategoryFilterClient(Container $container)
    {
        $container[static::CLIENT_PRODUCT_CATEGORY_FILTER] = function (Container $container) {
            return new ProductCategoryFilterGuiToProductCategoryFilterClientBridge($container->getLocator()->productCategoryFilter()->client());
        };
    }
}
