<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Dependency\Repository;

interface ProductMeasurementUnitStorageToProductMeasurementUnitRepositoryInterface
{
    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementUnitEntityTransfer[]
     */
    public function getProductMeasurementUnitEntities(array $productMeasurementUnitIds);

    /**
     * @return string[]
     */
    public function getProductMeasurementUnitCodeMap();

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementSalesUnitEntityTransfer[]
     */
    public function getProductMeasurementSalesUnitEntities($productIds);
}
