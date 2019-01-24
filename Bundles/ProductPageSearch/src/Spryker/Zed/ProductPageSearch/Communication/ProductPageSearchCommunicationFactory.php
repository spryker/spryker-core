<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToPriceProductServiceInterface;
use Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 */
class ProductPageSearchCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilSanitizeServiceInterface
     */
    public function getUtilSanitizeService()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface
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
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceProductInterface
     */
    public function getPriceProductFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PRICE_PRODUCT);
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
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageMapExpanderInterface[]
     */
    public function getProductConcretePageMapExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGINS_PRODUCT_CONCRETE_PAGE_MAP_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageMapExpanderPluginInterface[]
     */
    public function getConcreteProductPageMapExpanderPlugins(): array
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::PLUGINS_CONCRETE_PRODUCT_PAGE_MAP_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToCategoryInterface
     */
    public function getCategoryFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_CATEGORY);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface
     */
    public function getStoreFacade()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductInterface
     */
    public function getProductFacade(): ProductPageSearchToProductInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Client\ProductPageSearchToCatalogPriceProductConnectorClientInterface
     */
    public function getCatalogPriceProductConnectorClient()
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::CLIENT_CATALOG_PRICE_PRODUCT_CONNECTOR);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToPriceProductServiceInterface
     */
    public function getPriceProductService(): ProductPageSearchToPriceProductServiceInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::SERVICE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceFacadeInterface
     */
    public function getPriceFacade(): ProductPageSearchToPriceFacadeInterface
    {
        return $this->getProvidedDependency(ProductPageSearchDependencyProvider::FACADE_PRICE);
    }
}
