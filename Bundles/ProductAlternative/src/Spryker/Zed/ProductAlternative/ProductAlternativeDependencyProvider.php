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

/**
 * @method \Spryker\Zed\ProductAlternative\ProductAlternativeConfig getConfig()
 */
class ProductAlternativeDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const PLUGINS_POST_PRODUCT_ALTERNATIVE_CREATE = 'PLUGINS_POST_PRODUCT_ALTERNATIVE_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_POST_PRODUCT_ALTERNATIVE_DELETE = 'PLUGINS_POST_PRODUCT_ALTERNATIVE_DELETE';

    /**
     * @var string
     */
    public const PLUGINS_ALTERNATIVE_PRODUCT_APPLICABLE = 'PLUGINS_ALTERNATIVE_PRODUCT_APPLICABLE';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'QUERY_PRODUCT_ABSTRACT';

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
        $container = $this->addPostProductAlternativeCreatePlugins($container);
        $container = $this->addPostProductAlternativeDeletePlugins($container);
        $container = $this->addAlternativeProductApplicablePlugins($container);

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
        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductAbstractPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductAlternativeToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductAlternativeToProductFacadeBridge(
                $container->getLocator()->product()->facade(),
            );
        });

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
    protected function addProductAbstractPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_ABSTRACT, $container->factory(function () {
            return SpyProductAbstractQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostProductAlternativeCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_PRODUCT_ALTERNATIVE_CREATE, function () {
            return $this->getPostProductAlternativeCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPostProductAlternativeDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_POST_PRODUCT_ALTERNATIVE_DELETE, function () {
            return $this->getPostProductAlternativeDeletePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAlternativeProductApplicablePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ALTERNATIVE_PRODUCT_APPLICABLE, function () {
            return $this->getAlternativeProductApplicablePlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductAlternativeCreatePluginInterface>
     */
    protected function getPostProductAlternativeCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductAlternativeDeletePluginInterface>
     */
    protected function getPostProductAlternativeDeletePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\AlternativeProductApplicablePluginInterface>
     */
    protected function getAlternativeProductApplicablePlugins(): array
    {
        return [];
    }
}
