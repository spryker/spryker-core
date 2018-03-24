<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMeasurementUnit\Persistence\ProductMeasurementUnitRepository;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade\ProductMeasurementUnitStorageToProductMeasurementUnitFacadeBridge;
use Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository\ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryBridge;

class ProductMeasurementUnitStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    const FACADE_PRODUCT_MEASUREMENT_UNIT = 'FACADE_PRODUCT_MEASUREMENT_UNIT';

    const REPOSITORY_PRODUCT_MEASUREMENT_UNIT = 'REPOSITORY_PRODUCT_MEASUREMENT_UNIT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addProductMeasurementUnitRepository($container);
        $container = $this->addProductMeasurementUnitFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
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
    protected function addEventBehaviorFacade(Container $container)
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new ProductMeasurementUnitStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductMeasurementUnitFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_MEASUREMENT_UNIT] = function (Container $container) {
            return new ProductMeasurementUnitStorageToProductMeasurementUnitFacadeBridge(
                $container->getLocator()->productMeasurementUnit()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductMeasurementUnitRepository(Container $container)
    {
        $container[static::REPOSITORY_PRODUCT_MEASUREMENT_UNIT] = function (Container $container) {
            return new ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryBridge(
                // TODO: Find the repository
                new ProductMeasurementUnitRepository()
            );
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
        $container = parent::providePersistenceLayerDependencies($container);

        return $container;
    }
}
