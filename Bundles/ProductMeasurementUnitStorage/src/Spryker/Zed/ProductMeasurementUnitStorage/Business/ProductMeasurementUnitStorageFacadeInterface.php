<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business;

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
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findAllProductMeasurementUnitTransfers();

    /**
     * @api
     *
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function findProductMeasurementUnitTransfers(array $productMeasurementUnitIds);

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnits();

    /**
     * @api
     *
     * @param int[] $salesUnitIds
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]
     */
    public function getSalesUnitsByIds(array $salesUnitIds);
}
