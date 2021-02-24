<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\Dependency\Client\SalesToQuoteClientInterface;

class OrderReferenceManager
{
    private $quoteClient;

    public function __construct(SalesToQuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    public function checkOrderReference(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        if ($quoteTransfer->getOrderReference() !== null) {
            $checkoutResponseTransfer->getSaveOrder()->setOrderReference(
                $quoteTransfer->getOrderReference()
            );
        }

        return true;
    }

    public function setOrderReference(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($quoteTransfer->getOrderReference() === null
            && $checkoutResponseTransfer->getIsSuccess()
            && $checkoutResponseTransfer->getSaveOrder() !== null
        ) {
            $quoteTransfer->setOrderReference(
                $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
            );
            $this->quoteClient->setQuote($quoteTransfer);
        }
    }
}
