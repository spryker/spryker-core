<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

use Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer;

interface ProductConcreteMeasurementUnitStorageReaderInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductConcreteMeasurementUnitStorageTransfer
     */
    public function getProductConcreteMeasurementUnitStorageByIdProduct(int $idProduct): ProductConcreteMeasurementUnitStorageTransfer;
}
