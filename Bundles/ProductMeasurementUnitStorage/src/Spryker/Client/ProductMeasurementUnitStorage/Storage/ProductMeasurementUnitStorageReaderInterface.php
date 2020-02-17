<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer;

interface ProductMeasurementUnitStorageReaderInterface
{
    /**
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer|null
     */
    public function findProductMeasurementUnitStorage(int $idProductMeasurementUnit): ?ProductMeasurementUnitStorageTransfer;

    /**
     * @param string $mappingType
     * @param string[] $identifiers
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer[]
     */
    public function getProductMeasurementUnitsByMapping(string $mappingType, array $identifiers): array;

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer[]
     */
    public function getProductMeasurementUnitStorageCollection(array $productMeasurementUnitIds): array;
}
