<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductQuantityFacadeInterface
{
    /**
     * Specification:
     * - Checks if the quantity is positive.
     * - Uses SKU as fallback group key for cart change items when they are not provided.
     * - Validates product quantities if they fulfill all quantity restriction rules during item addition.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddProductQuantityRestrictions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Uses SKU as fallback group key for cart change items when they are not provided.
     * - Validates product quantities if they fulfill all quantity restriction rules during item removal.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemRemoveProductQuantityRestrictions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Retrieves product quantity transfers by provided product IDs.
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfersByProductIds(array $productIds): array;

    /**
     * Specification:
     * - Retrieves all product quantity transfers.
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findProductQuantityTransfers(): array;

    /**
     * Specification:
     * - Adjusts cart item quantity according to product quantity restrictions.
     * - Adds notification messages about adjustment.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function normalizeCartChangeTransferItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Checks if cart change transfer has normalizable items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $normalizableFields
     *
     * @return bool
     */
    public function hasCartChangeTransferNormalizableItems(CartChangeTransfer $cartChangeTransfer, array $normalizableFields): bool;

    /**
     * Specification:
     * - Retrieves product quantity transfers according to given offset and limit.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer>
     */
    public function findFilteredProductQuantityTransfers(FilterTransfer $filterTransfer): array;

    /**
     * Specification:
     * - Requires `QuoteTransfer.items.sku` to be set.
     * - Validates if quote items fulfill all quantity restriction rules during checkout.
     * - Adds all found errors to `CheckoutResponseTransfer.errors`.
     * - Returns `true` if no errors were found, `false` otherwise.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isValidItemQuantitiesOnCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;
}
