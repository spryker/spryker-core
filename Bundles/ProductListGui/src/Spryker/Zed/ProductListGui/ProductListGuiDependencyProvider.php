<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui;

use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeBridge;
use Spryker\Zed\ProductListGui\Dependency\Service\ProductListGuiToUtilCsvServiceBridge;

class ProductListGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';
    public const PROPEL_QUERY_CATEGORY = 'PROPEL_QUERY_CATEGORY';
    public const PROPEL_QUERY_CATEGORY_NODE = 'PROPEL_QUERY_CATEGORY_NODE';
    public const PROPEL_QUERY_PRODUCT_LIST = 'PROPEL_QUERY_PRODUCT_LIST';

    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    public const SERVICE_UTIL_CSV = 'SERVICE_UTIL_CSV';

    public const PLUGINS_PRODUCT_LIST_TABLE_ACTION_EXPANDER = 'PLUGINS_PRODUCT_LIST_TABLE_ACTION_EXPANDER';
    public const PLUGINS_PRODUCT_LIST_TABLE_CONFIG_EXPANDER = 'PLUGINS_PRODUCT_LIST_TABLE_CONFIG_EXPANDER';
    public const PLUGINS_PRODUCT_LIST_TABLE_DATA_EXPANDER = 'PLUGINS_PRODUCT_LIST_TABLE_DATA_EXPANDER';
    public const PLUGINS_PRODUCT_LIST_TABLE_HEADER_EXPANDER = 'PLUGINS_PRODUCT_LIST_TABLE_DATA_EXPANDER';

    public const PLUGINS_PRODUCT_LIST_OWNER_TYPE_FORM_EXPANDER = 'PLUGINS_PRODUCT_LIST_FORM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductListPropelQuery($container);

        $container = $this->addProductListFacade($container);
        $container = $this->addLocaleFacade($container);

        $container = $this->addUtilCsvService($container);

        $container = $this->addProductListTableActionExpanderPlugins($container);
        $container = $this->addProductListTableConfigExpanderPlugins($container);
        $container = $this->addProductListTableDataExpanderPlugins($container);
        $container = $this->addProductListTableHeaderExpanderPlugins($container);
        $container = $this->addProductListOwnerTypeFormExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addCategoryPropelQuery($container);
        $container = $this->addCategoryNodePropelQuery($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_LIST] = function () {
            return SpyProductListQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryNodePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CATEGORY_NODE] = function () {
            return SpyCategoryNodeQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT] = function () {
            return SpyProductQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CATEGORY] = function () {
            return SpyCategoryQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_LIST] = function ($container) {
            return new ProductListGuiToProductListFacadeBridge($container->getLocator()->productList()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilCsvService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_CSV] = function (Container $container) {
            return new ProductListGuiToUtilCsvServiceBridge(
                $container->getLocator()->utilCsv()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function ($container) {
            return new ProductListGuiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListTableActionExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_LIST_TABLE_ACTION_EXPANDER] = function () {
            return $this->getProductListTableActionExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListTableConfigExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_LIST_TABLE_CONFIG_EXPANDER] = function () {
            return $this->getProductListTableConfigExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListTableDataExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_LIST_TABLE_DATA_EXPANDER] = function () {
            return $this->getProductListTableDataExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListTableHeaderExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_LIST_TABLE_HEADER_EXPANDER] = function () {
            return $this->getProductListTableHeaderExpanderPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListOwnerTypeFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRODUCT_LIST_OWNER_TYPE_FORM_EXPANDER] = function () {
            return $this->getProductListOwnerTypeFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListOwnerTypeFormExpanderPluginInterface[]
     */
    protected function getProductListOwnerTypeFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableActionExpanderInterface[]
     */
    protected function getProductListTableActionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableConfigExpanderPluginInterface[]
     */
    protected function getProductListTableConfigExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableDataExpanderPluginInterface[]
     */
    protected function getProductListTableDataExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableDataExpanderPluginInterface[]
     */
    protected function getProductListTableHeaderExpanderPlugins(): array
    {
        return [];
    }
}
