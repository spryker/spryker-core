<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilMeasurementUnitConversionServiceBridge;

class ProductMeasurementUnitDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_MEASUREMENT_UNIT_CONVERSION = 'SERVICE_UTIL_MEASUREMENT_UNIT_CONVERSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilMeasurementUnitConversionService($container);

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
}
