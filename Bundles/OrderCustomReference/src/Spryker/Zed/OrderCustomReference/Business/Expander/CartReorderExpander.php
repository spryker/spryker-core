<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Business\Expander;

use Generated\Shared\Transfer\CartReorderTransfer;

class CartReorderExpander implements CartReorderExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function expandCartReorderQuoteWithOrderCustomReference(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        if ($cartReorderTransfer->getOrderOrFail()->getOrderCustomReference() === null) {
            return $cartReorderTransfer;
        }

        $cartReorderTransfer->getQuoteOrFail()->setOrderCustomReference(
            $cartReorderTransfer->getOrderOrFail()->getOrderCustomReferenceOrFail(),
        );

        return $cartReorderTransfer;
    }
}
