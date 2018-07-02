<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

class ProductMeasurementUnitReader implements ProductMeasurementUnitReaderInterface
{
    /**
     * @var \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitStorageReaderInterface
     */
    protected $productMeasurementUnitStorageReader;

    /**
     * @param \Spryker\Client\ProductMeasurementUnitStorage\Storage\ProductMeasurementUnitStorageReaderInterface $productMeasurementUnitStorageReader
     */
    public function __construct(
        ProductMeasurementUnitStorageReaderInterface $productMeasurementUnitStorageReader
    ) {
        $this->productMeasurementUnitStorageReader = $productMeasurementUnitStorageReader;
    }

    /**
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementUnit(int $idProductMeasurementUnit): ?ProductMeasurementUnitTransfer
    {
        $productMeasurementUnitStorageTransfer = $this->productMeasurementUnitStorageReader->findProductMeasurementUnitStorage($idProductMeasurementUnit);

        if ($productMeasurementUnitStorageTransfer === null) {
            return null;
        }

        return $this->mapProductMeasurementUnit(
            $productMeasurementUnitStorageTransfer,
            new ProductMeasurementUnitTransfer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer $measurementUnitStorageTransfer
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer $measurementUnitTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer
     */
    protected function mapProductMeasurementUnit(
        ProductMeasurementUnitStorageTransfer $measurementUnitStorageTransfer,
        ProductMeasurementUnitTransfer $measurementUnitTransfer
    ): ProductMeasurementUnitTransfer {
        $measurementUnitTransfer->fromArray(
            $measurementUnitStorageTransfer->toArray(),
            true
        );

        return $measurementUnitTransfer;
    }
}
