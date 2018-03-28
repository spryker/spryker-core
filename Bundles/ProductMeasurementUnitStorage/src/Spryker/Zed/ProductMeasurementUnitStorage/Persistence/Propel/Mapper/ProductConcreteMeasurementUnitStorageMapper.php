<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage;

class ProductConcreteMeasurementUnitStorageMapper implements ProductConcreteMeasurementUnitStorageMapperInterface
{
    /**
     * @param \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage $spyProductConcreteMeasurementUnitStorageEntity
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntity
     *
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage
     */
    public function hydrateSpyProductMeasurementUnitStorageEntity(
        SpyProductConcreteMeasurementUnitStorage $spyProductConcreteMeasurementUnitStorageEntity,
        SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntity
    ): SpyProductConcreteMeasurementUnitStorage {
        $spyProductConcreteMeasurementUnitStorageEntity->fromArray($productConcreteMeasurementUnitStorageEntity->toArray(true));

        return $spyProductConcreteMeasurementUnitStorageEntity;
    }
}
