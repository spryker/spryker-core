<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentCartConnector\Business\Remover;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuotePaymentRemoverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeQuotePayment(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function removeQuotePaymentOnCartChange(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
