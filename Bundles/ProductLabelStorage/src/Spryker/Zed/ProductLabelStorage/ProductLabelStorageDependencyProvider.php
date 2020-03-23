<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductLabelStorage\Dependency\Facade\ProductLabelStorageToProductLabelBridge;
use Spryker\Zed\ProductLabelStorage\Dependency\QueryContainer\ProductLabelStorageToProductLabelQueryContainerBridge;
use Spryker\Zed\ProductLabelStorage\Dependency\QueryContainer\ProductLabelStorageToProductQueryContainerBridge;

/**
 * @method \Spryker\Zed\ProductLabelStorage\ProductLabelStorageConfig getConfig()
 */
class ProductLabelStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    public const QUERY_CONTAINER_PRODUCT_LABEL = 'QUERY_CONTAINER_PRODUCT_LABEL';

    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';

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
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addProductLabelFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addProductLabelQueryContainer($container);
        $container = $this->addProductQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_LABEL, function (Container $container) {
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
    protected function addProductQueryContainer(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new ProductLabelStorageToProductQueryContainerBridge(
                $container->getLocator()->product()->queryContainer()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_LABEL, function (Container $container) {
            return new ProductLabelStorageToProductLabelBridge($container->getLocator()->productLabel()->facade());
        });

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
            return new ProductLabelStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        return $container;
    }
}
