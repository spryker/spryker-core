<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Persistence;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;
use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;

interface ProductMeasurementUnitRepositoryInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getProductMeasurementBaseUnitTransferByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer;

    /**
     * @param int $idProductMeasurementSalesUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer
     */
    public function getProductMeasurementSalesUnitTransfer(int $idProductMeasurementSalesUnit): ProductMeasurementSalesUnitTransfer;

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getProductMeasurementSalesUnitTransfersByIdProduct(int $idProduct): array;

    /**
     * @param int $idProductMeasurementBaseUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getProductMeasurementBaseUnitTransfer(int $idProductMeasurementBaseUnit): ProductMeasurementBaseUnitTransfer;

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;

    /**
     * @return string[] Keys are product measurement unit IDs, values are product measurement unit codes.
     */
    public function getProductMeasurementUnitCodeMap(): array;
}
