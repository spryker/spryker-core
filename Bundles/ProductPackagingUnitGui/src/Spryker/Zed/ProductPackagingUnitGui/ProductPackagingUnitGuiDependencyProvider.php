<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitGui;

use Orm\Zed\ProductPackagingUnit\Persistence\SpyProductPackagingUnitTypeQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToLocaleFacadeBridge;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductFacadeBridge;
use Spryker\Zed\ProductPackagingUnitGui\Dependency\Facade\ProductPackagingUnitGuiToProductPackagingUnitFacadeBridge;

class ProductPackagingUnitGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_PRODUCT_PACKAGING_UNIT = 'FACADE_PRODUCT_PACKAGING_UNIT';

    public const PROPEL_QUERY_PRODUCT_PACKAGING_UNIT_TYPE = 'PROPEL_QUERY_PRODUCT_PACKAGING_UNIT_TYPE';
    public const PROPEL_QUERY_SLAES_ORDER_ITEM = 'PROPEL_QUERY_SLAES_ORDER_ITEM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addProductPackagingUnitFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addLocaleFacade($container);

        $container = $this->addProductPackagingUnitTypePropelQuery($container);
        $container = $this->addSalesOrderItemPropelQuery($container);
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
            return new ProductPackagingUnitGuiToProductPackagingUnitFacadeBridge(
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
    protected function addProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new ProductPackagingUnitGuiToProductFacadeBridge(
                $container->getLocator()->product()->facade()
            );
        };

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
            return new ProductPackagingUnitGuiToLocaleFacadeBridge(
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
    protected function addProductPackagingUnitTypePropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_PACKAGING_UNIT_TYPE] = function (Container $container) {
            return new SpyProductPackagingUnitTypeQuery();
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
        $container[static::PROPEL_QUERY_SLAES_ORDER_ITEM] = function (Container $container) {
            return new SpySalesOrderItemQuery();
        };

        return $container;
    }
}
