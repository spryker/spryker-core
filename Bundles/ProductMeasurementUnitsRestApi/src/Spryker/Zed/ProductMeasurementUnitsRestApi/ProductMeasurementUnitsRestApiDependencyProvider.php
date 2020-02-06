<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitsRestApi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMeasurementUnitsRestApi\Dependency\Facade\ProductMeasurementUnitsRestApiToProductPackagingUnitFacadeBridge;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig getConfig()
 */
class ProductMeasurementUnitsRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_PACKAGING_UNIT = 'FACADE_PRODUCT_PACKAGING_UNIT';

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
    protected function addProductPackagingUnitFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_PACKAGING_UNIT, function (Container $container) {
            return new ProductMeasurementUnitsRestApiToProductPackagingUnitFacadeBridge(
                $container->getLocator()->productPackagingUnit()->facade()
            );
        });

        return $container;
    }
}
