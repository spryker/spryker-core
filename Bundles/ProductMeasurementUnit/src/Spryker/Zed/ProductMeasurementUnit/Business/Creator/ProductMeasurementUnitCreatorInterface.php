<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\Creator;

use Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer;

interface ProductMeasurementUnitCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitCollectionResponseTransfer
     */
    public function create(
        ProductMeasurementUnitCollectionRequestTransfer $productMeasurementUnitCollectionRequestTransfer
    ): ProductMeasurementUnitCollectionResponseTransfer;
}
