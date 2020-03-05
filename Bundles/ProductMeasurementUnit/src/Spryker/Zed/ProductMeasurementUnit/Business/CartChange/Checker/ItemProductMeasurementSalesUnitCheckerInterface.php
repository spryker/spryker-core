<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnit\Business\CartChange\Checker;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;

interface ItemProductMeasurementSalesUnitCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductMeasurementSalesUnit(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;
}
