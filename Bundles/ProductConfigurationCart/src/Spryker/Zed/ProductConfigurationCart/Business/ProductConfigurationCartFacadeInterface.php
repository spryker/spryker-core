<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductConfigurationCartFacadeInterface
{
    /**
     * Specification:
     * - Applicable to items which have product configuration attached.
     * - Expands item group key to include the product configuration key as it's key part.
     * - Returns modified `CartChangeTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductConfigurationItemsWithGroupKey(
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer;

    /**
     * Specification:
     * - Returns true if all product configuration items in the quote have complete configuration, false otherwise.
     * - If the quote item configuration is not available, an error code and message are added to the response.
     * - If the quote item configuration is incomplete, an error code and message are added to the response.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteProductConfigurationValid(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool;

    /**
     * Specification:
     * - Expands the list of price product transfers with product configuration prices.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expandPriceProductTransfersWithProductConfigurationPrices(
        array $priceProductTransfers,
        CartChangeTransfer $cartChangeTransfer
    ): array;

    /**
     * Specification:
     * - Finds given item in the cart.
     * - Counts item quantity by item SKU and product configuration instance.
     * - Returns quantity for the item.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): CartItemQuantityTransfer;

    /**
     * Specification:
     * - Finds given item in the cart.
     * - Counts item quantity by item SKU and product configuration instance in add and subtract directions.
     * - Returns quantity for the item.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countItemQuantity(CartChangeTransfer $cartChangeTransfer, ItemTransfer $itemTransfer): CartItemQuantityTransfer;

    /**
     * Specification:
     * - Validates configurable products in a quote request.
     * - Expects `QuoteRequestTransfer.latestVersion` and `QuoteRequestTransfer.latestVersion.quote` to be set.
     * - Returns "isSuccessful=true" if all items with a product configuration are fully configured.
     * - Returns "isSuccessful=false" and adds an error message if any item with product configuration is not fully configured.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteRequestTransfer $quoteRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteRequestResponseTransfer
     */
    public function validateQuoteRequestProductConfiguration(QuoteRequestTransfer $quoteRequestTransfer): QuoteRequestResponseTransfer;
}
