<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage;

use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductBridge;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeBridge;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToStoreFacadeInterface;
use Spryker\Zed\ProductStorage\Dependency\QueryContainer\ProductStorageToProductQueryContainerBridge;

/**
 * @method \Spryker\Zed\ProductStorage\ProductStorageConfig getConfig()
 */
class ProductStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_STORAGE_EXPANDER = 'PLUGINS_PRODUCT_ABSTRACT_STORAGE_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_STORAGE_COLLECTION_EXPANDER = 'PLUGINS_PRODUCT_CONCRETE_STORAGE_COLLECTION_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_ABSTRACT_STORAGE_COLLECTION_FILTER = 'PLUGINS_PRODUCT_ABSTRACT_STORAGE_COLLECTION_FILTER';

    /**
     * @var string
     */
    public const PLUGINS_PRODUCT_CONCRETE_STORAGE_COLLECTION_FILTER = 'PLUGINS_PRODUCT_CONCRETE_STORAGE_COLLECTION_FILTER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES = 'PROPEL_QUERY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addProductAbstractStorageExpanderPlugins($container);
        $container = $this->addProductConcreteStorageCollectionExpanderPlugins($container);
        $container = $this->addProductAbstractStorageCollectionFilterPlugins($container);
        $container = $this->addProductConcreteStorageCollectionFilterPlugins($container);
        $container = $this->addEventBehaviorFacade($container);

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
            return new ProductStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        });

        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductAbstractLocalizedAttributesPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container): ProductStorageToEventBehaviorFacadeInterface {
            return new ProductStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container->set(static::FACADE_PRODUCT, function (Container $container): ProductStorageToProductInterface {
            return new ProductStorageToProductBridge($container->getLocator()->product()->facade());
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
        $container->set(static::FACADE_STORE, function (Container $container): ProductStorageToStoreFacadeInterface {
            return new ProductStorageToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractStorageExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_STORAGE_EXPANDER, function () {
            return $this->getProductAbstractStorageExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageExpanderPluginInterface>
     */
    protected function getProductAbstractStorageExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteStorageCollectionExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_STORAGE_COLLECTION_EXPANDER, function () {
            return $this->getProductConcreteStorageCollectionExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionExpanderPluginInterface>
     */
    protected function getProductConcreteStorageCollectionExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractStorageCollectionFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_ABSTRACT_STORAGE_COLLECTION_FILTER, function () {
            return $this->getProductAbstractStorageCollectionFilterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductAbstractStorageCollectionFilterPluginInterface>
     */
    protected function getProductAbstractStorageCollectionFilterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteStorageCollectionFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PRODUCT_CONCRETE_STORAGE_COLLECTION_FILTER, function () {
            return $this->getProductConcreteStorageCollectionFilterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\ProductStorageExtension\Dependency\Plugin\ProductConcreteStorageCollectionFilterPluginInterface>
     */
    protected function getProductConcreteStorageCollectionFilterPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT, $container->factory(function (): SpyProductQuery {
            return SpyProductQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractLocalizedAttributesPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_ABSTRACT_LOCALIZED_ATTRIBUTES, $container->factory(function (): SpyProductAbstractLocalizedAttributesQuery {
            return SpyProductAbstractLocalizedAttributesQuery::create();
        }));

        return $container;
    }
}
