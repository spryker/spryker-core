<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationStorage;

use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductRelationStorage\Dependency\Facade\ProductRelationStorageToProductRelationFacadeBridge;
use Spryker\Zed\ProductRelationStorage\Dependency\QueryContainer\ProductRelationStorageToProductQueryContainerBridge;
use Spryker\Zed\ProductRelationStorage\Dependency\QueryContainer\ProductRelationStorageToProductRelationQueryContainerBridge;

/**
 * @method \Spryker\Zed\ProductRelationStorage\ProductRelationStorageConfig getConfig()
 */
class ProductRelationStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_RELATION = 'QUERY_CONTAINER_PRODUCT_RELATION';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_RELATION = 'FACADE_PRODUCT_RELATION';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_RELATION_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_RELATION_PRODUCT_ABSTRACT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductRelationFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addProductRelationFacade($container);
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
        $this->addProductRelationQueryContainer($container);
        $this->addProductQueryContainer($container);
        $this->addPropelProductRelationProductAbstractQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductRelationStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductRelationFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_RELATION, function (Container $container) {
            return new ProductRelationStorageToProductRelationFacadeBridge(
                $container->getLocator()->productRelation()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelProductRelationProductAbstractQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_RELATION_PRODUCT_ABSTRACT, $container->factory(function (): SpyProductRelationProductAbstractQuery {
            return SpyProductRelationProductAbstractQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new ProductRelationStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductRelationQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_RELATION, function (Container $container) {
            return new ProductRelationStorageToProductRelationQueryContainerBridge($container->getLocator()->productRelation()->queryContainer());
        });

        return $container;
    }
}
