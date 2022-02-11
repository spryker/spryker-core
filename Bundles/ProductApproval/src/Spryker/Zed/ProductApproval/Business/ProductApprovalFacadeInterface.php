<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;

interface ProductApprovalFacadeInterface
{
    /**
     * Specification:
     * - Gets the available product statuses for the current product status.
     * - Returns empty array if no available statuses exist.
     *
     * @api
     *
     * @param string $currentStatus
     *
     * @return array<string>
     */
    public function getApplicableApprovalStatuses(string $currentStatus): array;

    /**
     * Specification:
     * - Filters product abstract storage transfers by product approval status.
     * - Filters out abstract products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer> $productAbstractStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStorageTransfer>
     */
    public function filterProductAbstractStorageCollection(array $productAbstractStorageTransfers): array;

    /**
     * Specification:
     * - Filters product concrete storage transfers by product approval status.
     * - Filters out products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer> $productConcreteStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteStorageTransfer>
     */
    public function filterProductConcreteStorageCollection(array $productConcreteStorageTransfers): array;

    /**
     * Specification:
     * - Filters product page search transfers by product approval status.
     * - Filters out products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductPageSearchTransfer> $productPageSearchTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPageSearchTransfer>
     */
    public function filterProductPageSearchCollection(array $productPageSearchTransfers): array;

    /**
     * Specification:
     * - Filters product concrete transfers by product approval status.
     * - Filters out products which have not `approved` status.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function filterProductConcreteCollection(array $productConcreteTransfers): array;

    /**
     * Specification:
     * - Checks the approval status for products.
     * - Returns `CartPreCheckResponseTransfer` with an error in case cart items have not approved products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateCartChange(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Returns `false` response if at least one quote item transfer has items with not approved product.
     * - Sets error messages to checkout response if a quote contains not approved product items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validateQuoteForCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;

    /**
     * Specification:
     * - Checks the product approval status for shopping list item.
     * - Sets `ShoppingListPreAddItemCheckResponseTransfer.isSuccess` = true if a product is approved.
     * - Sets `ShoppingListPreAddItemCheckResponseTransfer.isSuccess` = false and adds a message if a product is not approved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function validateShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     * - Checks and removes not approved product items.
     * - Adds info messages for the removed products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterCartItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Expands `ProductAbstract` transfer with default approval status if `ProductAbstract.approvalStatus` property is not set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandProductAbstract(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer;
}
