<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage;

use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductAbstractImageStorageQuery;
use Orm\Zed\ProductImageStorage\Persistence\SpyProductConcreteImageStorageQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductImageResourceAliasStorage\Dependency\Facade\ProductImageResourceAliasStorageToEventBehaviorFacadeBridge;

class ProductImageResourceAliasStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT_IMAGE_STORAGE = 'PROPEL_QUERY_PRODUCT_ABSTRACT_IMAGE_STORAGE';
    public const PROPEL_QUERY_PRODUCT_CONCRETE_IMAGE_STORAGE = 'PROPEL_QUERY_PRODUCT_CONCRETE_IMAGE_STORAGE';
    public const PROPEL_QUERY_PRODUCT_IMAGE_SET = 'PROPEL_QUERY_PRODUCT_IMAGE_SET';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $this->addProductAbstractImageStoragePropelQuery($container);
        $this->addProductConcreteImageStoragePropelQuery($container);
        $this->addProductImageSetPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductImageResourceAliasStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAbstractImageStoragePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ABSTRACT_IMAGE_STORAGE] = function () {
            return SpyProductAbstractImageStorageQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductConcreteImageStoragePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_CONCRETE_IMAGE_STORAGE] = function () {
            return SpyProductConcreteImageStorageQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductImageSetPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_IMAGE_SET] = function () {
            return SpyProductImageSetQuery::create();
        };
        return $container;
    }
}
