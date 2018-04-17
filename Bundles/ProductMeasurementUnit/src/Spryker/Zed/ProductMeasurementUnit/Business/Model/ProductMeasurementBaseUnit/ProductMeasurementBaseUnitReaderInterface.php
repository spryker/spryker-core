<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Model\ProductMeasurementBaseUnit;

use Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer;

interface ProductMeasurementBaseUnitReaderInterface
{
    /**
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\SpyProductMeasurementBaseUnitEntityTransfer
     */
    public function getProductMeasurementBaseUnitEntityByIdProduct(int $idProduct): SpyProductMeasurementBaseUnitEntityTransfer;
}
