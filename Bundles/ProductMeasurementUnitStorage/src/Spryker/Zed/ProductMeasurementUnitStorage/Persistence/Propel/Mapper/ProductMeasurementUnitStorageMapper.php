<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer;
use Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorage;
use Spryker\Zed\ProductMeasurementUnitStorage\ProductMeasurementUnitStorageConfig;

class ProductMeasurementUnitStorageMapper implements ProductMeasurementUnitStorageMapperInterface
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
     * @param \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorage $spyProductMeasurementUnitStorageEntity
     * @param \Generated\Shared\Transfer\SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntityTransfer
     *
     * @return \Orm\Zed\ProductMeasurementUnitStorage\Persistence\SpyProductMeasurementUnitStorage
     */
    public function hydrateSpyProductMeasurementUnitStorageEntity(
        SpyProductMeasurementUnitStorage $spyProductMeasurementUnitStorageEntity,
        SpyProductMeasurementUnitStorageEntityTransfer $productMeasurementUnitStorageEntityTransfer
    ): SpyProductMeasurementUnitStorage {
        $spyProductMeasurementUnitStorageEntity->fromArray($productMeasurementUnitStorageEntityTransfer->toArray());
        $spyProductMeasurementUnitStorageEntity->setIsSendingToQueue(
            $this->config->isSendingToQueue()
        );

        return $spyProductMeasurementUnitStorageEntity;
    }
}
