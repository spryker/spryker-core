<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence;

use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorageQuery;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper\ProductConcreteMeasurementUnitStorageMapper;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper\ProductConcreteMeasurementUnitStorageMapperInterface;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper\ProductMeasurementUnitStorageMapper;
use Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper\ProductMeasurementUnitStorageMapperInterface;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig getConfig()
 */
class ProductMeasurementUnitStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorageQuery
     */
    public function createProductMeasurementUnitStorageQuery(): SpyProductMeasurementUnitStorageQuery
    {
        return SpyProductMeasurementUnitStorageQuery::create();
    }

    /**
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorageQuery
     */
    public function createProductConcreteMeasurementUnitStorageQuery(): SpyProductConcreteMeasurementUnitStorageQuery
    {
        return SpyProductConcreteMeasurementUnitStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper\ProductMeasurementUnitStorageMapperInterface
     */
    public function createProductMeasurementUnitStorageMapper(): ProductMeasurementUnitStorageMapperInterface
    {
        return new ProductMeasurementUnitStorageMapper();
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper\ProductConcreteMeasurementUnitStorageMapperInterface
     */
    public function createProductConcreteMeasurementUnitStorageMapper(): ProductConcreteMeasurementUnitStorageMapperInterface
    {
        return new ProductConcreteMeasurementUnitStorageMapper();
    }
}
