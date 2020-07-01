<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander\PricePageDataExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander\ProductCategoryPageDataExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageDataExpander\ProductImagePageDataExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageMapExpander\PricePageMapExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageMapExpander\ProductCategoryPageMapExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Communication\Plugin\PageMapExpander\ProductImagePageMapExpanderPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Client\ProductPageSearchToCatalogPriceProductConnectorClientBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToCategoryBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceFacadeBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceProductBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductImageFacadeBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeInterface;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToCategoryQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductCategoryQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductImageQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageToPriceProductQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToPriceProductServiceBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToPriceProductServiceInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilSanitizeServiceBridge;

/**
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductPageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';
    public const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';
    public const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    public const PROPEL_QUERY_CATEGORY_NODE = 'QUERY_CONTAINER_CATEGORY_NODE';
    public const QUERY_CONTAINER_PRICE = 'QUERY_CONTAINER_PRICE';
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';
    public const SERVICE_UTIL_ENCODING = 'util encoding service';
    public const SERVICE_PRICE_PRODUCT = 'SERVICE_PRICE_PRODUCT';
    public const CLIENT_CATALOG_PRICE_PRODUCT_CONNECTOR = 'CLIENT_CATALOG_PRICE_PRODUCT_CONNECTOR';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const FACADE_PRODUCT_SEARCH = 'FACADE_PRODUCT_SEARCH';
    public const FACADE_SEARCH = 'FACADE_SEARCH';
    public const FACADE_CATEGORY = 'FACADE_CATEGORY';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRICE = 'FACADE_PRICE';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    public const PLUGIN_PRODUCT_PAGE_DATA_EXPANDER = 'PLUGIN_PRODUCT_PAGE_DATA_EXPANDER';
    public const PLUGIN_PRODUCT_PAGE_DATA_LOADER = 'PLUGIN_PRODUCT_PAGE_DATA_LOADER';

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider::PLUGINS_PRODUCT_ABSTRACT_MAP_EXPANDER} instead.
     */
    public const PLUGIN_PRODUCT_PAGE_MAP_EXPANDER = 'PLUGIN_PRODUCT_PAGE_MAP_EXPANDER';
    public const PLUGIN_PRODUCT_PRICE_PAGE_DATA = 'PLUGIN_PRODUCT_PRICE_PAGE_DATA';
    public const PLUGIN_PRODUCT_CATEGORY_PAGE_DATA = 'PLUGIN_PRODUCT_CATEGORY_PAGE_DATA';
    public const PLUGIN_PRODUCT_IMAGE_PAGE_DATA = 'PLUGIN_PRODUCT_IMAGE_PAGE_DATA';
    public const PLUGINS_PRODUCT_CONCRETE_PAGE_DATA_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_PAGE_DATA_EXPANDER';
    public const PLUGINS_CONCRETE_PRODUCT_MAP_EXPANDER = 'PLUGINS_CONCRETE_PRODUCT_PAGE_MAP_EXPANDER';

    /**
     * @deprecated Use ProductPageSearchDependencyProvider::PLUGINS_CONCRETE_PRODUCT_PAGE_MAP_EXPANDER instead.
     */
    public const PLUGINS_PRODUCT_CONCRETE_PAGE_MAP_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_PAGE_MAP_EXPANDER';
    public const PROPEL_QUERY_PRODUCT_SEARCH = 'PROPEL_QUERY_PRODUCT_SEARCH';
    public const PLUGINS_PRODUCT_ABSTRACT_MAP_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_PAGE_MAP_EXPANDER';
    public const PROPEL_QUERY_PRICE_PRODUCT = 'PROPEL_QUERY_PRICE_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new ProductPageSearchToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        });

        $container->set(static::CLIENT_CATALOG_PRICE_PRODUCT_CONNECTOR, function (Container $container) {
            return new ProductPageSearchToCatalogPriceProductConnectorClientBridge($container->getLocator()->catalogPriceProductConnector()->client());
        });

        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductPageSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return new ProductPageSearchToCategoryBridge($container->getLocator()->category()->facade());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_IMAGE, function (Container $container) {
            return new ProductPageSearchToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_CATEGORY, function (Container $container) {
            return new ProductPageSearchToProductCategoryQueryContainerBridge($container->getLocator()->productCategory()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return new ProductPageSearchToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        });

        $container->set(static::PLUGIN_PRODUCT_PAGE_MAP_EXPANDER, function () {
            return $this->getMapExpanderPlugins();
        });

        $container->set(static::PLUGINS_PRODUCT_CONCRETE_PAGE_MAP_EXPANDER, function () {
            return $this->getProductConcretePageMapExpanderPlugins();
        });

        $container = $this->addProductConcreteMapExpanderPlugins($container);

        $container->set(static::PLUGINS_PRODUCT_CONCRETE_PAGE_DATA_EXPANDER, function () {
            return $this->getProductConcretePageDataExpanderPlugins();
        });

        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductPageSearchToProductBridge($container->getLocator()->product()->facade());
        });

        $container = $this->addPriceProductService($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addProductImageFacade($container);
        $container = $this->addProductSearchFacade($container);
        $container = $this->addPriceProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductPageSearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductPageSearchToProductBridge($container->getLocator()->product()->facade());
        });

        $container->set(static::FACADE_SEARCH, function (Container $container) {
            return new ProductPageSearchToSearchBridge($container->getLocator()->search()->facade());
        });

        $container->set(static::PLUGIN_PRODUCT_PAGE_DATA_EXPANDER, function () {
            return $this->getDataExpanderPlugins();
        });

        $container->set(static::PLUGINS_PRODUCT_CONCRETE_PAGE_DATA_EXPANDER, function () {
            return $this->getProductConcretePageDataExpanderPlugins();
        });

        $container->set(static::PLUGIN_PRODUCT_PAGE_DATA_LOADER, function () {
            return $this->getDataLoaderPlugins();
        });

        $container = $this->addStoreFacade($container);
        $container = $this->addProductImageFacade($container);
        $container = $this->addProductSearchFacade($container);
        $container = $this->addProductConcreteMapExpanderPlugins($container);
        $container = $this->addProductAbstractMapExpanderPlugins($container);
        $container = $this->addPriceProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new ProductPageSearchToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRICE, function (Container $container) {
            return new ProductPageToPriceProductQueryContainerBridge($container->getLocator()->priceProduct()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_IMAGE, function (Container $container) {
            return new ProductPageSearchToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_CATEGORY, function (Container $container) {
            return new ProductPageSearchToProductCategoryQueryContainerBridge($container->getLocator()->productCategory()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_CATEGORY, function (Container $container) {
            return new ProductPageSearchToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        });

        $container = $this->addCategoryNodePropelQuery($container);
        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductSearchPropelQuery($container);
        $container = $this->addPriceProductPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryNodePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_CATEGORY_NODE, $container->factory(function () {
            return SpyCategoryNodeQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT, $container->factory(function () {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductService(Container $container): Container
    {
        $container->set(static::SERVICE_PRICE_PRODUCT, function (Container $container): ProductPageSearchToPriceProductServiceInterface {
            return new ProductPageSearchToPriceProductServiceBridge(
                $container->getLocator()->priceProduct()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE, function (Container $container): ProductPageSearchToPriceFacadeInterface {
            return new ProductPageSearchToPriceFacadeBridge($container->getLocator()->price()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImageFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_IMAGE, function (Container $container) {
            return new ProductPageSearchToProductImageFacadeBridge($container->getLocator()->productImage()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container): ProductPageSearchToStoreFacadeInterface {
            return new ProductPageSearchToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductSearchPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_SEARCH, $container->factory(function () {
            return SpyProductSearchQuery::create();
        }));

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageDataExpanderInterface[]
     */
    protected function getDataExpanderPlugins()
    {
        return [
            ProductPageSearchConfig::PLUGIN_PRODUCT_PRICE_PAGE_DATA => new PricePageDataExpanderPlugin(),
            ProductPageSearchConfig::PLUGIN_PRODUCT_CATEGORY_PAGE_DATA => new ProductCategoryPageDataExpanderPlugin(),
            ProductPageSearchConfig::PLUGIN_PRODUCT_IMAGE_PAGE_DATA => new ProductImagePageDataExpanderPlugin(),
        ];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider::getProductAbstractPageMapExpanderPlugins()} instead.
     *
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageMapExpanderInterface[]
     */
    protected function getMapExpanderPlugins()
    {
        return [
            new PricePageMapExpanderPlugin(),
            new ProductCategoryPageMapExpanderPlugin(),
            new ProductImagePageMapExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface[]
     */
    protected function getDataLoaderPlugins()
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageMapExpanderPluginInterface[]
     */
    protected function getConcreteProductMapExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcretePageDataExpanderPluginInterface[]
     */
    protected function getProductConcretePageDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\ProductPageSearch\ProductPageSearchDependencyProvider::getConcreteProductPageMapExpanderPlugins()} instead.
     *
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageMapExpanderInterface[]
     */
    protected function getProductConcretePageMapExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductSearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_SEARCH, function (Container $container) {
            return new ProductPageSearchToProductSearchBridge($container->getLocator()->productSearch()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteMapExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CONCRETE_PRODUCT_MAP_EXPANDER, function () {
            return $this->getConcreteProductMapExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractMapExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_MAP_EXPANDER, function () {
            return $this->getProductAbstractMapExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface[]
     */
    protected function getProductAbstractMapExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRICE_PRODUCT, $container->factory(function () {
            return SpyPriceProductQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new ProductPageSearchToPriceProductBridge($container->getLocator()->priceProduct()->facade());
        });

        return $container;
    }
}
