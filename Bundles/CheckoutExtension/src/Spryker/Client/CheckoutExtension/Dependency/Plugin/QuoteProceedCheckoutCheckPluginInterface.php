<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteProceedCheckoutCheckPluginInterface
{
    /**
     * Specification:
     * - Returns CanProceedCheckoutResponseTransfer with array of Messages and isSuccessfull flag.
     * - Successfull if quote applicable for checkout.
     * - Unsuccessfull if quite is not applicable for checkout.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CanProceedCheckoutResponseTransfer
     */
    public function can(QuoteTransfer $quoteTransfer): CanProceedCheckoutResponseTransfer;
}
