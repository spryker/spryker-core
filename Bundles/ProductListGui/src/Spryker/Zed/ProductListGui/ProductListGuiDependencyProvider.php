<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui;

use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductListGui\Dependency\Facade\ProductListGuiToProductListFacadeBridge;

class ProductListGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';
    public const PROPEL_QUERY_CATEGORY_ATTRIBUTE = 'PROPEL_QUERY_CATEGORY_ATTRIBUTE';
    public const PROPEL_QUERY_PRODUCT_ATTRIBUTE = 'PROPEL_QUERY_PRODUCT_ATTRIBUTE';
    public const PLUGINS_FORM_EXTENSION = 'PLUGINS_FORM_EXTENSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addProductListFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductListCreateFormExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addCategoryAttributePropelQuery($container);
        $container = $this->addProductAttributePropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductListGuiToLocaleFacadeBridge(
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
    protected function addProductListFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_LIST] = function (Container $container) {
            return new ProductListGuiToProductListFacadeBridge(
                $container->getLocator()->productList()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryAttributePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_CATEGORY_ATTRIBUTE] = function (Container $container) {
            return new SpyCategoryAttributeQuery();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAttributePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ATTRIBUTE] = function (Container $container) {
            return new SpyProductLocalizedAttributesQuery();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListCreateFormExpanderPlugins(Container $container): Container
    {
        $container[static::PLUGINS_FORM_EXTENSION] = function (Container $container) {
            return $this->getProductListCreateFormExpanderPlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListCreateFormExpanderPluginInterface[]
     */
    protected function getProductListCreateFormExpanderPlugins(): array
    {
        return [];
    }
}
