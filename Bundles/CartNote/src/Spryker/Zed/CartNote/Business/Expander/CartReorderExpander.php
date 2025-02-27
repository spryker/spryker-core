<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartNote\Business\Expander;

use Generated\Shared\Transfer\CartReorderTransfer;

class CartReorderExpander implements CartReorderExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function expandCartReorderQuoteWithCartNote(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer
    {
        $cartReorderTransfer->getQuoteOrFail()->setCartNote(
            $cartReorderTransfer->getOrderOrFail()->getCartNote(),
        );

        return $cartReorderTransfer;
    }
}
