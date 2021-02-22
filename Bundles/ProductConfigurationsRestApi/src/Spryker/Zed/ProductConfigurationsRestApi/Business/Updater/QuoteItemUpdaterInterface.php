<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business\Updater;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteItemUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateQuoteItem(
        CartItemRequestTransfer $cartItemRequestTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): QuoteResponseTransfer;
}
