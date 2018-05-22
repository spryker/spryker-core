<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade;

use Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer;

interface ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getBaseUnitByIdProduct(int $idProduct): SpyProductMeasurementBaseUnitEntityTransfer;

    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array;

    /**
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap(): array;

    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    public function findProductMeasurementUnitEntities(array $productMeasurementUnitIds): array;
}
