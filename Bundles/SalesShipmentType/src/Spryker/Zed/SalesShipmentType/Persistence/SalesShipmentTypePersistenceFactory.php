<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesShipmentQuery;
use Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\SalesShipmentType\Persistence\Propel\Mapper\SalesShipmentTypeMapper;
use Spryker\Zed\SalesShipmentType\SalesShipmentTypeDependencyProvider;

/**
 * @method \Spryker\Zed\SalesShipmentType\SalesShipmentTypeConfig getConfig()
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeRepositoryInterface getRepository()
 */
class SalesShipmentTypePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SalesShipmentType\Persistence\SpySalesShipmentTypeQuery
     */
    public function createSalesShipmentTypeQuery(): SpySalesShipmentTypeQuery
    {
        return SpySalesShipmentTypeQuery::create();
    }

    /**
     * @return \Spryker\Zed\SalesShipmentType\Persistence\Propel\Mapper\SalesShipmentTypeMapper
     */
    public function createSalesShipmentTypeMapper(): SalesShipmentTypeMapper
    {
        return new SalesShipmentTypeMapper();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesShipmentQuery
     */
    public function getSalesShipmentPropelQuery(): SpySalesShipmentQuery
    {
        return $this->getProvidedDependency(SalesShipmentTypeDependencyProvider::PROPEL_QUERY_SALES_SHIPMENT);
    }
}
