<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class OrderReferenceManager
{
    public function restrictDuplicatedOrder(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        // TODO: restrict order creation

        if ($quoteTransfer->getOrderReference() !== null) {
            $checkoutResponseTransfer->addError(
            // TODO: add proper message with translation
                (new CheckoutErrorTransfer())->setMessage('Order already exists')
            );
            // TODO: maybe redirect to orders page?
            return false;
        }

        return true;
    }

    public function setOrderReference(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($checkoutResponseTransfer->getSaveOrder() !== null) {
            $quoteTransfer->setOrderReference(
                $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
            );
        }

        // TODO: needs quoteClient
    }
}
