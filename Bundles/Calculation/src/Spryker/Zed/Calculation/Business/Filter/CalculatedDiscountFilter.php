<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CalculatedDiscountCollectionTransfer;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;

class CalculatedDiscountFilter implements CalculatedDiscountFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculatedDiscountCollectionTransfer $calculatedDiscountCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CalculatedDiscountCollectionTransfer
     */
    public function filterOutEmptyCalculatedDiscounts(
        CalculatedDiscountCollectionTransfer $calculatedDiscountCollectionTransfer
    ): CalculatedDiscountCollectionTransfer {
        $filteredCalculatedDiscountTransfers = array_filter(
            $calculatedDiscountCollectionTransfer->getCalculatedDiscounts()->getArrayCopy(),
            function (CalculatedDiscountTransfer $calculatedDiscountTransfer) {
                return $calculatedDiscountTransfer->getUnitAmount() && $calculatedDiscountTransfer->getSumAmount();
            },
        );

        return $calculatedDiscountCollectionTransfer->setCalculatedDiscounts(
            new ArrayObject($filteredCalculatedDiscountTransfers),
        );
    }
}
