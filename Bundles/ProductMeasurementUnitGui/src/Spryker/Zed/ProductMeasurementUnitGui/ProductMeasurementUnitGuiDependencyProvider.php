<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui;

use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductMeasurementUnitGui\Dependency\Facade\ProductMeasurementUnitGuiToProductMeasurementUnitFacadeBridge;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitGui\ProductMeasurementUnitGuiConfig getConfig()
 */
class ProductMeasurementUnitGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_MEASUREMENT_UNIT = 'FACADE_PRODUCT_MEASUREMENT_UNIT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_PRODUCT_MEASUREMENT_UNIT = 'PROPEL_QUERY_PRODUCT_MEASUREMENT_UNIT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addProductMeasurementUnitFacade($container);
        $container = $this->addProductMeasurementUnitPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductMeasurementUnitPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_PRODUCT_MEASUREMENT_UNIT, $container->factory(function () {
            return SpyProductMeasurementUnitQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductMeasurementUnitFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_MEASUREMENT_UNIT, function (Container $container) {
            return new ProductMeasurementUnitGuiToProductMeasurementUnitFacadeBridge(
                $container->getLocator()->productMeasurementUnit()->facade(),
            );
        });

        return $container;
    }
}
