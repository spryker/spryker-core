<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CheckoutExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteProceedCheckoutCheckPluginInterface
{
    /**
     * Specification:
     * - Returns true if quote applicable for checkout.
     * - Returns false if quite is not applicable for checkout.
     * - If at least one plugin returns false - quote is not applicable for checkout.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function can(QuoteTransfer $quoteTransfer): bool;
}
