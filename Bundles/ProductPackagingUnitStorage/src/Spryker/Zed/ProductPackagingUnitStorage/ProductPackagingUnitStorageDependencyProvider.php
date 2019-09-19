<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductPackagingUnitStorage\Dependency\Facade\ProductPackagingUnitStorageToProductPackagingUnitFacadeBridge;

/**
 * @method \Spryker\Zed\ProductPackagingUnitStorage\ProductPackagingUnitStorageConfig getConfig()
 */
class ProductPackagingUnitStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_PRODUCT = 'PROPEL_QUERY_PRODUCT';
    public const PROPEL_QUERY_PRODUCT_PACKAGING_UNIT = 'PROPEL_QUERY_PRODUCT_PACKAGING_LEAD_PRODUCT';
    public const FACADE_PRODUCT_PACKAGING_UNIT = 'FACADE_PRODUCT_PACKAGING_UNIT';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

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
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addProductPackagingUnitFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addProductPropelQuery($container);
        $container = $this->addProductPackagingUnitPropelQuery($container);

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
    protected function addProductPackagingUnitPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_PACKAGING_UNIT, function () {
            return SpyProductPackagingUnitQuery::create();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPackagingUnitFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_PACKAGING_UNIT] = function (Container $container) {
            return new ProductPackagingUnitStorageToProductPackagingUnitFacadeBridge(
                $container->getLocator()->productPackagingUnit()->facade()
            );
        };

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
            return new ProductPackagingUnitStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        };

        return $container;
    }
}
