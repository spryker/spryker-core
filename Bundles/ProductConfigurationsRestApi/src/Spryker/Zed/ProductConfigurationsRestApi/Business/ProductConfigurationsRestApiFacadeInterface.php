<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Business;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface ProductConfigurationsRestApiFacadeInterface
{
    /**
     * Specification:
     * - Maps `CartItemRequestTransfer.productConfigurationInstance` to according `PersistentCartChangeTransfer.item`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function mapCartItemRequestTransferToPersistentCartChangeTransfer(
        CartItemRequestTransfer $cartItemRequestTransfer,
        PersistentCartChangeTransfer $persistentCartChangeTransfer
    ): PersistentCartChangeTransfer;

    /**
     * Specification:
     * - Checks if item to be updated is present in `QuoteResponseTransfer.quote`.
     * - If item is present, updates Persistent quote item.
     * - Returns error in case when updated item is missing in `QuoteResponseTransfer.quote`.
     *
     * @api
     *
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
