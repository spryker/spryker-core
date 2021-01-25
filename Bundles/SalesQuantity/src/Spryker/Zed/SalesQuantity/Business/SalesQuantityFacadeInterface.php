<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesQuantity\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\DiscountableItemTransformerTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;

/**
 * @method \Spryker\Zed\SalesQuantity\Business\SalesQuantityBusinessFactory getFactory()
 */
interface SalesQuantityFacadeInterface
{
    /**
     * Specification:
     * - Adds unchanged item to the returned item collection according to the non-splittable strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function transformNonSplittableItem(ItemTransfer $itemTransfer): ItemCollectionTransfer;

    /**
     * Specification:
     * - Reads a persisted concrete product from database.
     * - Expands the items of the CartChangeTransfer with a specific concrete product's data.
     * - Returns the expanded CartChangeTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartChangeWithIsQuantitySplittable(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;

    /**
     * Specification:
     * - Transforms discountable item according to the non splittable strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransformerTransfer
     */
    public function transformNonSplittableDiscountableItem(
        DiscountableItemTransformerTransfer $discountableItemTransformerTransfer
    ): DiscountableItemTransformerTransfer;

    /**
     * Specification:
     * - Checks if the item is splittable per quantity.
     * - Returns true if the item is a bundled item.
     * - Returns false if the product is non-splittable.
     * - Returns false if the item exceeded the preconfigured quantity threshold.
     * - Returns true in any other case.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    public function isItemQuantitySplittable(ItemTransfer $itemTransfer);
}
