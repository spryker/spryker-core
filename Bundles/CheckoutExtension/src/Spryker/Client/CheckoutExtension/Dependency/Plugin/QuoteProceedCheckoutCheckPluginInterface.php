<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;

interface QuoteProceedCheckoutCheckPluginInterface
{
    /**
     * Specification:
     * - Returns QuoteValidationResponseTransfer with array of Messages and isSuccessful flag.
     * - Successful if quote applicable for checkout.
     * - Unsuccessful if quite is not applicable for checkout.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function isValid(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer;
}
