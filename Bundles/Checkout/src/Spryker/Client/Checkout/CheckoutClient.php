<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Checkout\CheckoutFactory getFactory()
 */
class CheckoutClient extends AbstractClient implements CheckoutClientInterface
{
    /**
     * Places the order
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function placeOrder(QuoteTransfer $quoteTransfer)
    {
        return $this->getZedStub()->placeOrder($quoteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function isQuoteApplicableForCheckout(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteProceedCheckoutChecker()
            ->isQuoteApplicableForCheckout($quoteTransfer);
    }

    /**
     * @return \Spryker\Client\Checkout\Zed\CheckoutStubInterface
     */
    protected function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }
}
