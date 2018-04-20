<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;

interface ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getBaseUnitByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer;

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array;

    /**
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap(): array;

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;
}
