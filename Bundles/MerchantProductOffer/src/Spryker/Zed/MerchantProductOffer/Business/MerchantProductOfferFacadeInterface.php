<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business;

use Generated\Shared\Transfer\MerchantProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;

interface MerchantProductOfferFacadeInterface
{
    /**
     * Specification:
     *  - Gets ProductOfferCollectionTransfer filtered by MerchantProductOfferCriteriaTransfer.
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
}
