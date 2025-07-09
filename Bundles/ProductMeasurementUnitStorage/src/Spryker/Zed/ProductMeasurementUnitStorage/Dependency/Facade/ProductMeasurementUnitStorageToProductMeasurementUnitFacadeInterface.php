<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Facade;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductMeasurementUnitStorageToProductMeasurementUnitFacadeInterface
{
    /**
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getSalesUnitsByIdProduct(int $idProduct): array;

    /**
     * @param array<int> $salesUnitsIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getSalesUnitsByIds(array $salesUnitsIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function getSalesUnits(): array;

    /**
     * @param array<int> $productMeasurementUnitIds
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;

    /**
     * @deprecated Will be removed without replacement. Unused method.
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findAllProductMeasurementUnitTransfers(): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementUnitTransfer>
     */
    public function findFilteredProductMeasurementUnitTransfers(FilterTransfer $filterTransfer): array;

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer>
     */
    public function findFilteredProductMeasurementSalesUnitTransfers(FilterTransfer $filterTransfer): array;
}
