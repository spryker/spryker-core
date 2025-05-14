<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpySalesOrderItemProductAbstractTypeQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use SprykerFeature\Zed\SspServiceManagement\Persistence\Mapper\ProductAbstractTypeMapper;
use SprykerFeature\Zed\SspServiceManagement\Persistence\Mapper\SspServiceMapper;
use SprykerFeature\Zed\SspServiceManagement\Persistence\Propel\AbstractSpySalesProductAbstractTypeQuery;
use SprykerFeature\Zed\SspServiceManagement\SspServiceManagementDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface getEntityManager()
 */
class SspServiceManagementPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery
     */
    public function createProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return SpyProductShipmentTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractTypeQuery
     */
    public function createProductAbstractTypeQuery(): SpyProductAbstractTypeQuery
    {
        return SpyProductAbstractTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractTypeQuery
     */
    public function createProductAbstractToProductAbstractTypeQuery(): SpyProductAbstractToProductAbstractTypeQuery
    {
        return SpyProductAbstractToProductAbstractTypeQuery::create();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Persistence\Mapper\ProductAbstractTypeMapper
     */
    public function createProductAbstractTypeMapper(): ProductAbstractTypeMapper
    {
        return new ProductAbstractTypeMapper();
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpySalesOrderItemProductAbstractTypeQuery
     */
    public function createSalesOrderItemProductAbstractTypeQuery(): SpySalesOrderItemProductAbstractTypeQuery
    {
        return SpySalesOrderItemProductAbstractTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpySalesProductAbstractTypeQuery
     */
    public function createSalesProductAbstractTypeQuery(): AbstractSpySalesProductAbstractTypeQuery
    {
        return AbstractSpySalesProductAbstractTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function createSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return SpySalesOrderItemQuery::create();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Persistence\Mapper\SspServiceMapper
     */
    public function createSspServiceMapper(): SspServiceMapper
    {
        return new SspServiceMapper($this->getOmsFacade());
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_OMS);
    }
}
