<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface PaymentCartConnectorFacadeInterface
{
    /**
     * Specification:
     *  - Removes the payment information from the quote if not excluded by configuration {@link \Spryker\Zed\PaymentCartConnector\PaymentCartConnectorConfig::getExcludedPaymentMethods()}.
     *  - Expects `QuoteTransfer.payment` and `QuoteTransfer.payments` to be set.
     *  - Removes `Quote.payment` and `Quote.payments`.
     *  - Returns the modified quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeQuotePayment(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Removes the payment information from the quote when cart changes are made.
     * - Expects `QuoteTransfer.payment` and `QuoteTransfer.payments` to be set.
     * - Removes `Quote.payment` and `Quote.payments`.
     * - Returns the modified `CartChangeTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function removeQuotePaymentOnCartChange(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
