<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Plugin interface is used to check if cart item is found in Quote.
 *
 * Runs before any cart item operation such as update or delete.
 */
interface QuoteItemCheckerPluginInterface
{
    /**
     * Specification:
     * - Used to check if the item is found in `QuoteTransfer` before performing cart item operation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkItemIsInQuote(CartItemRequestTransfer $cartItemRequestTransfer, QuoteTransfer $quoteTransfer): bool;
}
