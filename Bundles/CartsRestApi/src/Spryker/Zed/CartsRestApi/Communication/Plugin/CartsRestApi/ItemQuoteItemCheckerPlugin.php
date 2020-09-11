<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Communication\Plugin\CartsRestApi;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteItemCheckerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CartsRestApi\Business\CartsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 */
class ItemQuoteItemCheckerPlugin extends AbstractPlugin implements QuoteItemCheckerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if the cart item is a simple item and present in `QuoteTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkItemIsInQuote(CartItemRequestTransfer $cartItemRequestTransfer, QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFacade()->isItemInQuote($cartItemRequestTransfer, $quoteTransfer);
    }
}
