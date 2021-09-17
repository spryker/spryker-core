<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage\Storage;

interface ProductMeasurementSalesUnitReaderInterface
{
    /**
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>|null
     */
    public function findProductMeasurementSalesUnitByIdProduct(int $idProduct): ?array;

    /**
     * @param array<int> $productConcreteIds
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteProductMeasurementSalesUnitTransfer>
     */
    public function getProductMeasurementSalesUnitsByProductConcreteIds(array $productConcreteIds): array;
}
