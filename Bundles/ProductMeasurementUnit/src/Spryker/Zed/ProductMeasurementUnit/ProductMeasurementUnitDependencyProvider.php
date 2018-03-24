<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMeasurementUnit\Dependency\Service\ProductMeasurementUnitToUtilUnitConversionServiceBridge;

class ProductMeasurementUnitDependencyProvider extends AbstractBundleDependencyProvider
{
    const SERVICE_UTIL_UNIT_CONVERSION = 'SERVICE_UTIL_UNIT_CONVERSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addUtilUnitConversionService($container);

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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilUnitConversionService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_UNIT_CONVERSION] = function (Container $container) {
            return new ProductMeasurementUnitToUtilUnitConversionServiceBridge($container->getLocator()->utilUnitConversion()->service());
        };

        return $container;
    }
}
