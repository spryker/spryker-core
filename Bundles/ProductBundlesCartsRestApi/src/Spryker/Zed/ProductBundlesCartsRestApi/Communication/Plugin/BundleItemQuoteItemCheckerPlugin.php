<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundlesCartsRestApi\Communication\Plugin;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteItemCheckerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundlesCartsRestApi\ProductBundlesCartsRestApiConfig getConfig()
 * @method \Spryker\Zed\ProductBundlesCartsRestApi\Business\ProductBundlesCartsRestApiFacadeInterface getFacade()
 */
class BundleItemQuoteItemCheckerPlugin extends AbstractPlugin implements QuoteItemCheckerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if `CartItemRequestTransfer` is a bundle item in the `QuoteTransfer`.
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
        return $this->getFacade()->isBundleItemInQuote($cartItemRequestTransfer, $quoteTransfer);
    }
}
