<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductMeasurementUnitStorage;

interface ProductMeasurementUnitStorageClientInterface
{
    /**
     * Specification:
     * - Finds a product measurement unit within Storage with a given ID.
     * - Returns null if product measurement unit was not found.
     *
     * @api
     *
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer|null
     */
    public function findProductMeasurementUnit($idProductMeasurementUnit);

    /**
     * Specification:
     * - Finds a product concrete measurement unit within Storage with a given ID.
     * - Returns null if product concrete measurement unit was not found.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer|null
     */
    public function findProductConcreteMeasurementUnit($idProduct);
}
