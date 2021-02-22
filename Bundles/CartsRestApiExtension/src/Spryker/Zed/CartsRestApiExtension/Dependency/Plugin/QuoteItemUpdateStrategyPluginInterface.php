<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

/**
 * Use this plugin for quote item update.
 */
interface QuoteItemUpdateStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if strategy plugin is applicable, based on cart item request data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CartItemRequestTransfer $cartItemRequestTransfer): bool;

    /**
     * Specification:
     * - Updates quote item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function update(CartItemRequestTransfer $cartItemRequestTransfer, QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer;
}
