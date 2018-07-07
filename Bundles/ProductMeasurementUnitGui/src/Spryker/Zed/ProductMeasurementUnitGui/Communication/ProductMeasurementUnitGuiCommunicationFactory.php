<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitGui\Communication;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ProductMeasurementUnitGui\Communication\Hydrator\OrderHydrator;
use Spryker\Zed\ProductMeasurementUnitGui\Communication\Hydrator\OrderHydratorInterface;
use Spryker\Zed\ProductMeasurementUnitGui\ProductMeasurementUnitGuiDependencyProvider;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitGui\ProductMeasurementUnitGuiConfig getConfig()
 */
class ProductMeasurementUnitGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductMeasurementUnitGui\Communication\Hydrator\OrderHydratorInterface
     */
    public function createOrderHydrator(): OrderHydratorInterface
    {
        return new OrderHydrator(
            $this->getSalesOrderItemPropelQuery()
        );
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(ProductMeasurementUnitGuiDependencyProvider::PROPEL_QUERY_SLAES_ORDER_ITEM);
    }
}
