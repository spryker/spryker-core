<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;

class DiscountPromotionItemFilter implements DiscountPromotionItemFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterDiscountPromotionItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $filteredItemTransfers = [];

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getIdDiscountPromotion()) {
                continue;
            }

            $filteredItemTransfers[] = $itemTransfer;
        }

        return $cartChangeTransfer->setItems(new ArrayObject($filteredItemTransfers));
    }
}
