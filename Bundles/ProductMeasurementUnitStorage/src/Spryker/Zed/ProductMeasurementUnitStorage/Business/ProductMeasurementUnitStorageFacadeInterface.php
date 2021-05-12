<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;

interface ProductMeasurementUnitStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes product measurement unit changes to storage.
     *
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return void
     */
    public function publishProductMeasurementUnit(array $productMeasurementUnitIds): void;

    /**
     * Specification:
     * - Publishes product concrete measurement unit changes to storage.
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishProductConcreteMeasurementUnit(array $productIds): void;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers(): array;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getSalesUnits(): array;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int[] $salesUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getSalesUnitsByIds(array $salesUnitIds): array;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function findFilteredProductMeasurementUnitTransfers(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractTransfer[]
     */
    public function findFilteredProductMeasurementSalesUnitTransfers(FilterTransfer $filterTransfer): array;
}
