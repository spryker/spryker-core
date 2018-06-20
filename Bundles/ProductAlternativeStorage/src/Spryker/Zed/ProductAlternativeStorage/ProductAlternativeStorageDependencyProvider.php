<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductAlternative\Persistence\SpyProductAlternativeQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductAlternativeStorage\Dependency\Facade\ProductAlternativeStorageToEventBehaviorFacadeBridge;

class ProductAlternativeStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_ALTERNATIVE = 'FACADE_PRODUCT_ALTERNATIVE';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';
    public const PROPEL_QUERY_PRODUCT_ABSTRACT = 'PROPEL_QUERY_PRODUCT_ABSTRACT';
    public const PROPEL_QUERY_PRODUCT_ALTERNATIVE = 'PROPEL_QUERY_PRODUCT_ALTERNATIVE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addEventBehaviorFacade($container);

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
        $container = $this->addProductAbstractPropelQuery($container);
        $container = $this->addProductAlternativePropelQuery($container);
        $container = $this->addProductPropelQuery($container);

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
            return new ProductAlternativeStorageToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductAlternativePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ALTERNATIVE] = function () {
            return SpyProductAlternativeQuery::create();
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
    protected function addProductAbstractPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_ABSTRACT] = function () {
            return SpyProductAbstractQuery::create();
        };

        return $container;
    }
}
