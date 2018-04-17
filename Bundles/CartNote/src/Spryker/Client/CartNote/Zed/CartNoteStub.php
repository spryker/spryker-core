<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartNote\Zed;

use Generated\Shared\Transfer\QuoteCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\ZedRequest\Stub\ZedRequestStub;

class CartNoteStub extends ZedRequestStub implements CartNoteStubInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function setNoteToQuote(QuoteCartNoteRequestTransfer $quoteCartNoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->zedStub->call('/cart-note/gateway/set-quote-note', $quoteCartNoteRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function setNoteToQuoteItem(QuoteItemCartNoteRequestTransfer $quoteItemCartNoteRequestTransfer): QuoteResponseTransfer
    {
        return $this->zedStub->call('/cart-note/gateway/set-quote-item-note', $quoteItemCartNoteRequestTransfer);
    }
}
