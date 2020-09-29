<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundleCartsRestApi\Communication\Plugin;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteItemReadValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductBundleCartsRestApi\ProductBundleCartsRestApiConfig getConfig()
 * @method \Spryker\Zed\ProductBundleCartsRestApi\Business\ProductBundleCartsRestApiFacadeInterface getFacade()
 */
class BundleItemQuoteItemReadValidatorPlugin extends AbstractPlugin implements QuoteItemReadValidatorPluginInterface
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
    public function validate(CartItemRequestTransfer $cartItemRequestTransfer, QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFacade()->validateBundleItem($cartItemRequestTransfer, $quoteTransfer);
    }
}
