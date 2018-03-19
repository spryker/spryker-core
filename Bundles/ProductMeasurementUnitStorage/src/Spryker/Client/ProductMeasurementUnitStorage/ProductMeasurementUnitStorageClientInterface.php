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
     * -
     *
     * @api
     *
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitStorageTransfer|null
     */
    public function getProductMeasurementUnit($idProductMeasurementUnit);

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer|null
     */
    public function getProductConcreteMeasurementUnit($idProduct);
}
