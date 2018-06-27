<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeBridge;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeBridge;

class ProductAlternativeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const QUERY_PRODUCT = 'QUERY_PRODUCT';
    public const QUERY_PRODUCT_ABSTRACT = 'QUERY_PRODUCT_ABSTRACT';
    public const PLUGINS_POST_PRODUCT_ALTERNATIVE = 'PLUGINS_POST_PRODUCT_ALTERNATIVE';
    public const PLUGINS_DELETE_POST_PRODUCT_ALTERNATIVE = 'PLUGINS_DELETE_POST_PRODUCT_ALTERNATIVE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addPostProductAlternativePlugins($container);
        $container = $this->addPostDeleteProductAlternativePlugins($container);

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
        $container = $this->addProductQuery($container);
        $container = $this->addProductAbstractQuery($container);

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
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductAlternativeToProductFacadeBridge(
                $container->getLocator()->product()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQuery(Container $container): Container
    {
        $container[static::QUERY_PRODUCT] = function () {
            return SpyProductQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractQuery(Container $container): Container
    {
        $container[static::QUERY_PRODUCT_ABSTRACT] = function () {
            return SpyProductAbstractQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostProductAlternativePlugins(Container $container): Container
    {
        $container[static::PLUGINS_POST_PRODUCT_ALTERNATIVE] = function () {
            return $this->getPostProductAlternativePlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostDeleteProductAlternativePlugins(Container $container): Container
    {
        $container[static::PLUGINS_DELETE_POST_PRODUCT_ALTERNATIVE] = function () {
            return $this->getPostDeleteProductAlternativePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductUpdateAlternativesPluginInterface[]
     */
    protected function getPostProductAlternativePlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostDeleteProductAlternativePluginInterface[]
     */
    protected function getPostDeleteProductAlternativePlugins(): array
    {
        return [];
    }
}
