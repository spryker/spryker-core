<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;

interface MerchantProductOfferFacadeInterface
{
    /**
     * Specification:
     * - Gets ProductOfferCollectionTransfer filtered by MerchantProductOfferCriteriaTransfer.
     * - Uses `MerchantProductOfferCriteriaTransfer.MerchantProductOfferConditions.skus` to filter product offers by skus.
     * - Uses `MerchantProductOfferCriteriaTransfer.MerchantProductOfferConditions.isActive` to filter product offers by isActive.
     * - Uses `MerchantProductOfferCriteriaTransfer.MerchantProductOfferConditions.merchantReferences` to filter product offers by merchant references.
     * - Uses `MerchantProductOfferCriteriaTransfer.MerchantProductOfferConditions.storeIds` to filter product offers by store ids.
     * - Uses `MerchantProductOfferCriteriaTransfer.pagination.limit` and `MerchantProductOfferCriteriaTransfer.pagination.offset` to paginate results with limit and offset.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollection(
        MerchantProductOfferCriteriaTransfer $merchantProductOfferCriteriaTransfer
    ): ProductOfferCollectionTransfer;

    /**
     * Specification:
     * - Populates shopping list item collection with merchant reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemCollectionTransfer
     */
    public function expandShoppingListItemCollection(
        ShoppingListItemCollectionTransfer $shoppingListItemCollectionTransfer
    ): ShoppingListItemCollectionTransfer;

    /**
     * Specification:
     * - Validates that merchant of product offer is active.
     * - Validates that merchant of product offer is approved.
     * - Uses `ShoppingListItemTransfer.productOfferReference` to find corresponding product offer.
     * - Skips validation if `ShoppingListItemTransfer.productOfferReference` is not provided.
     * - Skips validation if product offer is not found by `ShoppingListItemTransfer.productOfferReference` provided.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItem(ShoppingListItemTransfer $shoppingListItemTransfer): ShoppingListPreAddItemCheckResponseTransfer;

    /**
     * Specification:
     * - Expands product concrete collection with offers.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcretesWithOffers(array $productConcreteTransfers): array;
}
