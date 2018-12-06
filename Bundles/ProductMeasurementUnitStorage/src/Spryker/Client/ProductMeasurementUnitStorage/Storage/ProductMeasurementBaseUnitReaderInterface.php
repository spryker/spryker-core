<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;

interface ProductMeasurementBaseUnitReaderInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementBaseUnitByIdProduct(int $idProduct): ?ProductMeasurementUnitTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function expandProductConcreteTransferWithBaseMeasurementUnit(array $productConcreteTransfers): array;
}
