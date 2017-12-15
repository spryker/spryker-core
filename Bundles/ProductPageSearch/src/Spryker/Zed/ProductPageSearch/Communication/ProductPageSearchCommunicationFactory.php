<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilSanitizeServiceInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainer getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductPageSearchCommunicationFactory extends AbstractCommunicationFactory
{

    /**
     * @return ProductPageSearchToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return ProductPageSearchToEventBehaviorFacadeInterface
     */
    public function getEventBehaviorFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }
    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchInterface
     */
    public function getProductSearchFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PRODUCT_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceInterface
     */
    public function getPriceFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PRICE);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductImageQueryContainerBridge
     */
    public function getProductImageQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductCategoryQueryContainerBridge
     */
    public function getProductCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_PRODUCT_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToCategoryQueryContainerBridge
     */
    public function getCategoryQueryContainer()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::QUERY_CONTAINER_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageMapExpanderInterface[]
     */
    public function getProductPageMapExpanderPlugins()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGIN_PRODUCT_PAGE_MAP_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToCategoryInterface
     */
    public function getCategoryFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_CATEGORY);
    }

}
