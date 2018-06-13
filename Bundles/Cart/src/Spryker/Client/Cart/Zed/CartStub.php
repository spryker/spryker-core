<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Zed;

use Generated\Shared\Transfer\CartChangeQuantityTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class CartStub extends ZedRequestStub implements CartStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addValidItems(CartChangeTransfer $cartChangeTransfer): QuoteTransfer
    {
        return $this->zedStub->call('/cart/gateway/add-valid-items', $cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItem(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/add-item', $cartChangeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $changeTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem(CartChangeTransfer $changeTransfer)
    {
        return $this->zedStub->call('/cart/gateway/remove-item', $changeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function reloadItems(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/cart/gateway/reload-items', $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeQuantityTransfer $cartChangeQuantityTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function changeItemQuantity(CartChangeQuantityTransfer $cartChangeQuantityTransfer): QuoteResponseTransfer
    {
        return $this->zedStub->call('/cart/gateway/change-item-quantity', $cartChangeQuantityTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function validateQuote(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/cart/gateway/validate-quote', $quoteTransfer);
    }
}
