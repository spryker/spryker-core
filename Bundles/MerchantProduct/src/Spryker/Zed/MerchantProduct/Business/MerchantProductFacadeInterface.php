<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MerchantProductCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShoppingListItemCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Generated\Shared\Transfer\ValidationResponseTransfer;

interface MerchantProductFacadeInterface
{
    /**
     * Specification:
     * - Finds merchant for the given abstract product id.
     * - Returns found MerchantTransfer or null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findMerchant(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): ?MerchantTransfer;

    /**
     * Specification:
     * - Finds merchant products by provided MerchantProductCriteria.
     * - Returns MerchantProductCollection transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductCollectionTransfer
     */
    public function get(MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer): MerchantProductCollectionTransfer;

    /**
     * Specification:
     * - Validates that merchant references in the cart items match existing merchant products.
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
     * - Finds merchant product by provided MerchantProductCriteria.
     * - Sets corresponding abstract product if exists.
     * - Returns null if merchant product not found.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer|null
     */
    public function findMerchantProduct(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?MerchantProductTransfer;

    /**
     * Specification:
     * - Validates abstract product belongs to a merchant.
     * - Returns ValidationResponseTransfer transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function validateMerchantProduct(MerchantProductTransfer $merchantProductTransfer): ValidationResponseTransfer;

    /**
     * Specification:
     * - Requires MerchantProductCriteria.idMerchant transfer field to be set.
     * - Returns the list of concrete products related to the provided merchant by criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ProductConcreteCollectionTransfer;

    /**
     * Specification:
     * - Returns concrete product by provided criteria.
     * - Requires at least 1 ID in MerchantProductCriteria.productConcreteIds transfer field to be set.
     * - Requires MerchantProductCriteria.idMerchant transfer field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcrete(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?ProductConcreteTransfer;

    /**
     * Specification:
     * - Returns true if concrete product belongs to merchant, false otherwise.
     * - Requires Merchant.idMerchant and ProductConcrete.idProductConcrete transfer fields to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return bool
     */
    public function isProductConcreteOwnedByMerchant(
        ProductConcreteTransfer $productConcreteTransfer,
        MerchantTransfer $merchantTransfer
    ): bool;

    /**
     * Specification:
     * - Creates a new merchant product abstract entity.
     * - Requires MerchantProductTransfer.idMerchant and MerchantProductTransfer.idProductAbstract.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductTransfer $merchantProductTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function create(MerchantProductTransfer $merchantProductTransfer): MerchantProductTransfer;

    /**
     * Specification:
     * - Returns true if abstract product belongs to merchant, false otherwise.
     * - Requires MerchantTransfer.idMerchant and ProductAbstractTransfer.idProductAbstract.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return bool
     */
    public function isProductAbstractOwnedByMerchant(
        ProductAbstractTransfer $productAbstractTransfer,
        MerchantTransfer $merchantTransfer
    ): bool;

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
     * - Requires `ShoppingListItemTransfer.merchantReference` to be set.
     * - Checks that merchant of merchant product in shopping list is active and approved.
     * - Returns `ShoppingListPreAddItemCheckResponse` transfer object with error messages.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function checkShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer;
}
