<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Filter;

use Generated\Shared\Transfer\CalculatedDiscountCollectionTransfer;

interface CalculatedDiscountFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountCollectionTransfer $calculatedDiscountCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountCollectionTransfer
     */
    public function filterOutEmptyCalculatedDiscounts(
        CalculatedDiscountCollectionTransfer $calculatedDiscountCollectionTransfer
    ): CalculatedDiscountCollectionTransfer;
}
