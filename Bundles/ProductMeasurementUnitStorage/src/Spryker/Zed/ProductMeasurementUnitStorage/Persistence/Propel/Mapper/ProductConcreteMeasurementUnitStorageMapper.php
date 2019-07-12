<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage;
use Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;

class ProductConcreteMeasurementUnitStorageMapper implements ProductConcreteMeasurementUnitStorageMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig $config
     */
    public function __construct(ProductMeasurementUnitStorageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage $spyProductConcreteMeasurementUnitStorageEntity
     * @param \Generated\Shared\Transfer\SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntityTransfer
     *
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductConcreteMeasurementUnitStorage
     */
    public function hydrateSpyProductMeasurementUnitStorageEntity(
        SpyProductConcreteMeasurementUnitStorage $spyProductConcreteMeasurementUnitStorageEntity,
        SpyProductConcreteMeasurementUnitStorageEntityTransfer $productConcreteMeasurementUnitStorageEntityTransfer
    ): SpyProductConcreteMeasurementUnitStorage {
        $spyProductConcreteMeasurementUnitStorageEntity->fromArray($productConcreteMeasurementUnitStorageEntityTransfer->toArray(true));
        $spyProductConcreteMeasurementUnitStorageEntity->setIsSendingToQueue(
            $this->config->isSendingToQueue()
        );

        return $spyProductConcreteMeasurementUnitStorageEntity;
    }
}
