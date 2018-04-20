<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit;

use Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer;

interface ProductMeasurementBaseUnitReaderInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementBaseUnitTransfer
     */
    public function getProductMeasurementBaseUnitTransferByIdProduct(int $idProduct): ProductMeasurementBaseUnitTransfer;
}
