<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToAvailabilityFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToGlossaryFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToLocaleFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToOmsFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToProductMeasurementUnitFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStockFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Dependency\Facade\ProductPackagingUnitToStoreFacadeBridge;
use Spryker\Zed\ProductPackagingUnit\Dependency\QueryContainer\ProductPackagingUnitToSalesQueryContainerBridge;

class ProductPackagingUnitDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_PRODUCT_MEASUREMENT_UNIT = 'FACADE_PRODUCT_MEASUREMENT_UNIT';
    public const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';
    public const FACADE_OMS = 'FACADE_OMS';
    public const FACADE_STOCK = 'FACADE_STOCK';
    public const FACADE_STORE = 'FACADE_STORE';

    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addProductMeasurementUnitFacade($container);
        $container = $this->addAvailabilityFacade($container);
        $container = $this->addOmsFacade($container);
        $container = $this->addStockFacade($container);
        $container = $this->addStoreFacade($container);

        $container = $this->addSalesQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductPackagingUnitToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container[static::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductPackagingUnitToGlossaryFacadeBridge(
                $container->getLocator()->glossary()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductMeasurementUnitFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_MEASUREMENT_UNIT] = function (Container $container) {
            return new ProductPackagingUnitToProductMeasurementUnitFacadeBridge(
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
    protected function addAvailabilityFacade(Container $container): Container
    {
        $container[static::FACADE_AVAILABILITY] = function (Container $container) {
            return new ProductPackagingUnitToAvailabilityFacadeBridge(
                $container->getLocator()->availability()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container): Container
    {
        $container[static::FACADE_OMS] = function (Container $container) {
            return new ProductPackagingUnitToOmsFacadeBridge(
                $container->getLocator()->oms()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container): Container
    {
        $container[static::FACADE_STOCK] = function (Container $container) {
            return new ProductPackagingUnitToStockFacadeBridge(
                $container->getLocator()->stock()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new ProductPackagingUnitToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesQueryContainer(Container $container): Container
    {
        $container[static::QUERY_CONTAINER_SALES] = function (Container $container) {
            return new ProductPackagingUnitToSalesQueryContainerBridge(
                $container->getLocator()->sales()->queryContainer()
            );
        };

        return $container;
    }
}
