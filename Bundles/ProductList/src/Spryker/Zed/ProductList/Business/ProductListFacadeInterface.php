<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ProductListTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductListFacadeInterface
{
    /**
     * Specification:
     * - Creates a Product List entity if ProductListTransfer::idProductList is null.
     * - Creates relations to categories.
     * - Creates relations to concrete products.
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Updates fields in a Product List entity if ProductListTransfer::idProductList is set.
     * - Updates relations to categories.
     * - Updates relations to concrete products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function saveProductList(ProductListTransfer $productListTransfer): ProductListTransfer;

    /**
     * Specification:
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Deletes Product List.
     * - Deletes relations to categories.
     * - Deletes relations to concrete products.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void;

    /**
     * Specification:
     *  - Retrieves product abstract blacklists by product abstract id.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * Specification:
     *  - Retrieves product lists for product abstract ids and its categories.
     *  - Return format is: [idProductAbstract => [type => [idProductList...]].
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractListsIdsByIdProductAbstractIn(array $productAbstractIds): array;

    /**
     * Specification:
     *  - Retrieves product lists for product ids and its abstract products.
     *  - Return format is: [idProduct => [type => [idProductList...]].
     *
     * @api
     *
     * @param int[] $productConcreteIds
     *
     * @return array
     */
    public function getProductListsIdsByIdProductIn(array $productConcreteIds): array;

    /**
     * Specification:
     *  - Retrieves product abstract whitelists by product abstract id.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * Specification:
     *  - Retrieves category whitelists by product abstract id.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getCategoryWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * Specification:
     *  - Retrieves product concrete whitelists by product abstract id.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductConcrete(int $idProductConcrete): array;

    /**
     * Specification:
     *  - Retrieves product concrete whitelists by product abstract id.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductConcrete(int $idProductConcrete): array;

    /**
     * Specification:
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Hydrate ProductListTransfer and relations to products and categories.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListTransfer
     */
    public function getProductListById(ProductListTransfer $productListTransfer): ProductListTransfer;

    /**
     * Specification:
     *  - Retrieves product abstract ids whitelists by product list ids.
     *
     * @api
     *
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductListIds(array $productListIds): array;

    /**
     * Specification:
     * - Validates product if they whitelisted or blacklisted.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddProductListRestrictions(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer;

    /**
     * Specification:
     *  - Removes restricted items from quote.
     *  - Adds note to messages about removed items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterRestrictedItems(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
