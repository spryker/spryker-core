<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationsRestApi\Communication\Plugin\CartsRestApi;

use Generated\Shared\Transfer\CartItemRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Zed\CartsRestApiExtension\Dependency\Plugin\QuoteItemUpdateStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductConfigurationsRestApi\Business\ProductConfigurationsRestApiFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationsRestApi\ProductConfigurationsRestApiConfig getConfig()
 */
class ProductConfigurationQuoteItemUpdateStrategyPlugin extends AbstractPlugin implements QuoteItemUpdateStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks that `CartItemRequestTransfer.productConfigurationInstance` is set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartItemRequestTransfer $cartItemRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CartItemRequestTransfer $cartItemRequestTransfer): bool
    {
        return $cartItemRequestTransfer->getProductConfigurationInstance() !== null;
    }

    /**
     * {@inheritDoc}
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
    public function update(CartItemRequestTransfer $cartItemRequestTransfer, QuoteResponseTransfer $quoteResponseTransfer): QuoteResponseTransfer
    {
        return $this->getFacade()->updateQuoteItem($cartItemRequestTransfer, $quoteResponseTransfer);
    }
}
