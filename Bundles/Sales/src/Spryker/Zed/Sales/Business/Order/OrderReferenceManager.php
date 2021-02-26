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
    /**
     * @var \Spryker\Zed\Sales\Dependency\Client\SalesToQuoteClientInterface
     */
    private $quoteClient;

    /**
     * @param \Spryker\Zed\Sales\Dependency\Client\SalesToQuoteClientInterface $quoteClient
     */
    public function __construct(SalesToQuoteClientInterface $quoteClient)
    {
        $this->quoteClient = $quoteClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function updateQuoteOrderReference(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        if ($this->isReadyToQuoteUpdate($quoteTransfer, $checkoutResponseTransfer)) {
            $quoteTransfer->setOrderReference(
                $checkoutResponseTransfer->getSaveOrder()->getOrderReference()
            );
            $this->quoteClient->setQuote($quoteTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    private function isReadyToQuoteUpdate(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        return $quoteTransfer->getOrderReference() === null
            && $checkoutResponseTransfer->getIsSuccess()
            && $checkoutResponseTransfer->getSaveOrder() !== null;
    }
}
