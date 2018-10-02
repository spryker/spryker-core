<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage;

use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductImageStorage\Dependency\Facade\ProductImageStorageToProductImageBridge;
use Spryker\Zed\ProductImageStorage\Dependency\QueryContainer\ProductImageStorageToProductImageQueryContainerBridge;
use Spryker\Zed\ProductImageStorage\Dependency\QueryContainer\ProductImageStorageToProductQueryContainerBridge;

class ProductImageStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';
    public const PROPEL_QUERY_PRODUCT_LOCALIZED_ATTRIBUTES = 'PROPEL_QUERY_PRODUCT_LOCALIZED_ATTRIBUTES';
    public const PROPEL_QUERY_PRODUCT_IMAGE_SET = 'PROPEL_QUERY_PRODUCT_IMAGE_SET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductImageStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
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
        $container[static::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductImageStorageToProductImageBridge($container->getLocator()->productImage()->facade());
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
        $container = $this->addProductQueryContainer($container);
        $container = $this->addProductImageQueryContainer($container);
        $container = $this->addPropelProductLocalizedAttributesQuery($container);
        $container = $this->addPropelProductImageSetQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductImageStorageToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImageQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductImageStorageToProductImageQueryContainerBridge(
                $container->getLocator()->productImage()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelProductLocalizedAttributesQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_LOCALIZED_ATTRIBUTES] = function (): SpyProductLocalizedAttributesQuery {
            return SpyProductLocalizedAttributesQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelProductImageSetQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_IMAGE_SET] = function (): SpyProductImageSetQuery {
            return SpyProductImageSetQuery::create();
        };

        return $container;
    }
}
