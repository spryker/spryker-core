<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType;

use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\SalesShipmentType\SalesShipmentTypeConfig getConfig()
 */
class SalesShipmentTypeDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PROPEL_QUERY_SALES_SHIPMENT = 'PROPEL_QUERY_SALES_SHIPMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addSalesShipmentPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesShipmentPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_SHIPMENT, $container->factory(function () {
            return SpySalesShipmentQuery::create();
        }));

        return $container;
    }
}
