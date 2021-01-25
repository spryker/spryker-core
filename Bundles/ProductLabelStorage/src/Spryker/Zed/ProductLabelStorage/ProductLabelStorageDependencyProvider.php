<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage;

use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeBridge;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelFacadeInterface;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToStoreFacadeBridge;
use Spryker\Zed\ProductLabelStorage\Dependency\QueryContainer\ProductLabelStorageToProductLabelQueryContainerBridge;
use Spryker\Zed\ProductLabelStorage\Dependency\QueryContainer\ProductLabelStorageToProductLabelQueryContainerInterface;

/**
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 */
class ProductLabelStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_PRODUCT_LABEL = 'QUERY_CONTAINER_PRODUCT_LABEL';

    public const PROPEL_QUERY_PRODUCT_LABEL = 'PROPEL_QUERY_PRODUCT_LABEL';

    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductLabelFacade($container);

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
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductLabelFacade($container);
        $container = $this->addStoreFacade($container);

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
        $container = $this->addProductLabelQueryContainer($container);
        $container = $this->addProductLabelPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container): ProductLabelStorageToEventBehaviorFacadeInterface {
            return new ProductLabelStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_LABEL, function (Container $container): ProductLabelStorageToProductLabelQueryContainerInterface {
            return new ProductLabelStorageToProductLabelQueryContainerBridge(
                $container->getLocator()->productLabel()->queryContainer()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_LABEL, $container->factory(function (): SpyProductLabelQuery {
            return SpyProductLabelQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LABEL, function (Container $container): ProductLabelStorageToProductLabelFacadeInterface {
            return new ProductLabelStorageToProductLabelFacadeBridge(
                $container->getLocator()->productLabel()->facade()
            );
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
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductLabelStorageToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }
}
