<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductMeasurementUnit\Persistence\Propel\Mapper\ProductMeasurementUnitMapper;
use Spryker\Zed\ProductMeasurementUnit\Persistence\Propel\Mapper\ProductMeasurementUnitMapperInterface;

/**
 * @method \Spryker\Zed\ProductMeasurementUnit\ProductMeasurementUnitConfig getConfig()
 */
class ProductMeasurementUnitPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery
     */
    public function createProductMeasurementSalesUnitQuery(): SpyProductMeasurementSalesUnitQuery
    {
        return SpyProductMeasurementSalesUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery
     */
    public function createProductMeasurementBaseUnitQuery(): SpyProductMeasurementBaseUnitQuery
    {
        return SpyProductMeasurementBaseUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery
     */
    public function createProductMeasurementUnitQuery(): SpyProductMeasurementUnitQuery
    {
        return SpyProductMeasurementUnitQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnit\Persistence\Propel\Mapper\ProductMeasurementUnitMapperInterface
     */
    public function createProductMeasurementUnitMapper(): ProductMeasurementUnitMapperInterface
    {
        return new ProductMeasurementUnitMapper();
    }
}
