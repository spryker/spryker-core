<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\CartReorderTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferResponseTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductOfferFacadeInterface
{
    /**
     * Specification:
     * - Returns collection of product offer by provided criteria.
     * - Pagination is controlled with page, maxPerPage, nbResults, previousPage, nextPage, firstIndex, lastIndex, firstPage and lastPage values.
     * - Result might be filtered with concreteSku(s), productOfferReference(s) values.
     * - Uses `ProductOfferCriteriaTransfer.productOfferConditions.productOfferIds` to filter by product offer IDs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function get(ProductOfferCriteriaTransfer $productOfferCriteria): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Finds ProductOfferTransfer by provided ProductOfferCriteriaTransfer.
     * - Result might be filtered with concreteSku(s), productOfferReference(s) values.
     * - Uses `ProductOfferCriteriaTransfer.productOfferConditions.productOfferIds` to filter by product offer IDs.
     * - Executes ProductOfferExpanderPluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteria
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findOne(ProductOfferCriteriaTransfer $productOfferCriteria): ?ProductOfferTransfer;

    /**
     * Specification:
     * - Generates product offer reference and sets default approval status if they are not set.
     * - Creates a product offer.
     * - Creates relations between a product offer and stores.
     * - Executes ProductOfferPostCreatePluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function create(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer;

    /**
     * Specification:
     * - Returns ProductOfferResponseTransfer.isSuccessful=false if $productOfferTransfer.idProductOffer is not given.
     * - Returns ProductOfferResponseTransfer.isSuccessful=false if no offer is found with $productOfferTransfer.idProductOffer.
     * - Persists product offer entity with modified fields from ProductOfferTransfer.
     * - Returns new product offer entity in ProductOfferResponseTransfer.productOffer and isSuccessful=true.
     * - Updates relations between a product offer and stores.
     * - Executes ProductOfferPostUpdatePluginInterface plugin stack.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferResponseTransfer
     */
    public function update(ProductOfferTransfer $productOfferTransfer): ProductOfferResponseTransfer;

    /**
     * Specification:
     * - Removes inactive offer items from quote.
     * - Adds info messages for the removed product offers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveProductOfferItems(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Check if cart items product offer belongs to product.
     * - Returns pre-check transfer with error messages in case of error.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkItemProductOffer(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     * - Gets the available product offer statuses for the current product offer status.
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
     * - Returns `false` response if at least one quote item transfer has items with inactive or not approved ProductOffer.
     * - Sets error messages to checkout response, in case if items contain inactive or not approved ProductOffer items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteReadyForCheckout(
        QuoteTransfer $quoteTransfer,
        CheckoutResponseTransfer $checkoutResponseTransfer
    ): bool;

    /**
     * Specification:
     * - Finds given product offer item in the cart.
     * - Counts quantity for the given item.
     * - Returns counted quantity.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(
        ArrayObject $itemsInCart,
        ItemTransfer $itemTransfer
    ): CartItemQuantityTransfer;

    /**
     * Specification:
     * - Fetches a collection of product offers from the Persistence.
     * - Uses `ProductOfferCriteriaTransfer.pagination.limit` and `ProductOfferCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     * - Uses `ProductOfferCriteriaTransfer.productOfferConditions.productOfferIds` to filter by product offer IDs.
     * - Uses `ProductOfferCriteriaTransfer.productOfferConditions.productOfferReferences` to filter by product offer references.
     * - Uses `ProductOfferCriteriaTransfer.productOfferConditions.storeIds` to filter by product offer stores.
     * - Returns `ProductOfferCollectionTransfer` filled with found product offers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCriteriaTransfer $productOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(ProductOfferCriteriaTransfer $productOfferCriteriaTransfer): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Requires `CartReorderTransfer.orderItems.idSalesOrderItem` to be set.
     * - Requires `CartReorderTransfer.orderItems.sku` to be set.
     * - Requires `CartReorderTransfer.orderItems.quantity` to be set.
     * - Requires `CartReorderTransfer.reorderItems.idSalesOrderItem` to be set.
     * - Extracts `CartReorderTransfer.orderItems` that have `ItemTransfer.productOfferReference` set.
     * - Expands `CartReorderTransfer.reorderItems` with product offer reference if item with provided `idSalesOrderItem` already exists.
     * - Adds new item with product offer reference, sku, quantity and ID sales order item properties set to `CartReorderTransfer.reorderItems` otherwise.
     * - Returns `CartReorderTransfer` with product offer reference set to reorder items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderTransfer $cartReorderTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderTransfer
     */
    public function hydrateCartReorderItemsWithProductOffer(CartReorderTransfer $cartReorderTransfer): CartReorderTransfer;
}
