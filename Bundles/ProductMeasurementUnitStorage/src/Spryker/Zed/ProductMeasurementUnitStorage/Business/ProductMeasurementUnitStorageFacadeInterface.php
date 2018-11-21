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
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers(): array;

    /**
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds): array;

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getSalesUnits(): array;

    /**
     * @api
     *
     * @param int[] $salesUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getSalesUnitsByIds(array $salesUnitIds): array;

    /**
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findProductMeasurementUnitTransfersByIdsFilteredByOffsetAndLimit(array $productMeasurementUnitIds, FilterTransfer $filterTransfer): array;

    /**
     * @api
     *
     * @param int[] $salesUnitIds
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function findSalesUnitsByIdsFilteredByOffsetAndLimit(array $salesUnitIds, FilterTransfer $filterTransfer): array;
}
