<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch;

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
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToPriceProductBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToProductSearchBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToSearchBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToStoreFacadeBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToCategoryQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductCategoryQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductImageQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageSearchToProductQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\QueryContainer\ProductPageToPriceProductQueryContainerBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingBridge;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilSanitizeServiceBridge;

class ProductPageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';
    const QUERY_CONTAINER_PRODUCT_CATEGORY = 'QUERY_CONTAINER_PRODUCT_CATEGORY';
    const QUERY_CONTAINER_CATEGORY = 'QUERY_CONTAINER_CATEGORY';
    const QUERY_CONTAINER_PRICE = 'QUERY_CONTAINER_PRICE';
    const SERVICE_UTIL_ENCODING = 'util encoding service';
    const CLIENT_CATALOG_PRICE_PRODUCT_CONNECTOR = 'CLIENT_CATALOG_PRICE_PRODUCT_CONNECTOR';
    const FACADE_PRODUCT = 'FACADE_PRODUCT';
    const FACADE_STORE = 'FACADE_STORE';
    const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    const FACADE_PRODUCT_SEARCH = 'FACADE_PRODUCT_SEARCH';
    const FACADE_SEARCH = 'FACADE_SEARCH';
    const FACADE_CATEGORY = 'FACADE_CATEGORY';
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';
    const PLUGIN_PRODUCT_PAGE_DATA_EXPANDER = 'PLUGIN_PRODUCT_PAGE_DATA_EXPANDER';
    const PLUGIN_PRODUCT_PAGE_MAP_EXPANDER = 'PLUGIN_PRODUCT_PAGE_MAP_EXPANDER';
    const PLUGIN_PRODUCT_PRICE_PAGE_DATA = 'PLUGIN_PRODUCT_PRICE_PAGE_DATA';
    const PLUGIN_PRODUCT_CATEGORY_PAGE_DATA = 'PLUGIN_PRODUCT_CATEGORY_PAGE_DATA';
    const PLUGIN_PRODUCT_IMAGE_PAGE_DATA = 'PLUGIN_PRODUCT_IMAGE_PAGE_DATA';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_SANITIZE] = function (Container $container) {
            return new ProductPageSearchToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        };

        $container[static::CLIENT_CATALOG_PRICE_PRODUCT_CONNECTOR] = function (Container $container) {
            return new ProductPageSearchToCatalogPriceProductConnectorClientBridge($container->getLocator()->catalogPriceProductConnector()->client());
        };

        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductPageSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        $container[static::FACADE_PRODUCT_SEARCH] = function (Container $container) {
            return new ProductPageSearchToProductSearchBridge($container->getLocator()->productSearch()->facade());
        };

        $container[self::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new ProductPageSearchToPriceProductBridge($container->getLocator()->priceProduct()->facade());
        };

        $container[self::FACADE_STORE] = function (Container $container) {
            return new ProductPageSearchToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        $container[self::FACADE_CATEGORY] = function (Container $container) {
            return new ProductPageSearchToCategoryBridge($container->getLocator()->category()->facade());
        };

        $container[self::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductPageSearchToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return new ProductPageSearchToProductCategoryQueryContainerBridge($container->getLocator()->productCategory()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return new ProductPageSearchToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        };

        $container[static::PLUGIN_PRODUCT_PAGE_MAP_EXPANDER] = function (Container $container) {
            return $this->getMapExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductPageSearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };

        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductPageSearchToProductBridge($container->getLocator()->product()->facade());
        };

        $container[self::FACADE_SEARCH] = function (Container $container) {
            return new ProductPageSearchToSearchBridge($container->getLocator()->search()->facade());
        };

        $container[static::PLUGIN_PRODUCT_PAGE_DATA_EXPANDER] = function (Container $container) {
            return $this->getDataExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductPageSearchToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_PRICE] = function (Container $container) {
            return new ProductPageToPriceProductQueryContainerBridge($container->getLocator()->priceProduct()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductPageSearchToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_PRODUCT_CATEGORY] = function (Container $container) {
            return new ProductPageSearchToProductCategoryQueryContainerBridge($container->getLocator()->productCategory()->queryContainer());
        };

        $container[self::QUERY_CONTAINER_CATEGORY] = function (Container $container) {
            return new ProductPageSearchToCategoryQueryContainerBridge($container->getLocator()->category()->queryContainer());
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageMapExpanderInterface[]
     */
    protected function getDataExpanderPlugins()
    {
        return [
            self::PLUGIN_PRODUCT_PRICE_PAGE_DATA => new PricePageDataExpanderPlugin(),
            self::PLUGIN_PRODUCT_CATEGORY_PAGE_DATA => new ProductCategoryPageDataExpanderPlugin(),
            self::PLUGIN_PRODUCT_IMAGE_PAGE_DATA => new ProductImagePageDataExpanderPlugin(),
        ];
    }

    /**
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
}
