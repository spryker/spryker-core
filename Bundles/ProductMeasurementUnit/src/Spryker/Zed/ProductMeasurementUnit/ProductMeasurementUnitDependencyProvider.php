<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToEventFacadeBridge;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToGlossaryFacadeBridge;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Facade\ProductMeasurementUnitToStoreFacadeBridge;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceBridge;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig getConfig()
 */
class ProductMeasurementUnitDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_MEASUREMENT_UNIT_CONVERSION = 'SERVICE_UTIL_MEASUREMENT_UNIT_CONVERSION';

    public const FACADE_EVENT = 'FACADE_EVENT';
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';
    public const FACADE_STORE = 'FACADE_STORE';

    public const PROPEL_QUERY_SALES_ORDER_ITEM = 'PROPEL_QUERY_SALES_ORDER_ITEM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilMeasurementUnitConversionService($container);
        $container = $this->addEventFacade($container);
        $container = $this->addSalesOrderItemPropelQuery($container);
        $container = $this->addGlossaryFacade($container);
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

        $container = $this->addSalesOrderItemPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT] = function (Container $container) {
            return new ProductMeasurementUnitToEventFacadeBridge($container->getLocator()->event()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilMeasurementUnitConversionService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_MEASUREMENT_UNIT_CONVERSION] = function (Container $container) {
            return new ProductMeasurementUnitToUtilMeasurementUnitConversionServiceBridge($container->getLocator()->utilMeasurementUnitConversion()->service());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderItemPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_SALES_ORDER_ITEM] = function () {
            return SpySalesOrderItemQuery::create();
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
        $container[self::FACADE_GLOSSARY] = function (Container $container) {
            return new ProductMeasurementUnitToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
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
            return new ProductMeasurementUnitToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }
}
