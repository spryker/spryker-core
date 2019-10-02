<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductList\Business;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ProductListResponseTransfer;
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
     * - Executes ProductListPreSaveInterface plugin stack before save.
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
     * - Requires ProductListTransfer::title.
     * - Creates a Product List entity.
     * - Creates relations to categories.
     * - Creates relations to concrete products.
     * - Executes ProductListPreCreatePluginInterface plugin stack before save.
     * - Returns MessageTransfers in messages property to notify about changes that have been made to Product List.
     * - Returns true isSuccess property if saving was successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function createProductList(ProductListTransfer $productListTransfer): ProductListResponseTransfer;

    /**
     * Specification:
     * - Requires ProductListTransfer::idProductList.
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Updates fields in a Product List entity if ProductListTransfer::idProductList is set.
     * - Updates relations to categories.
     * - Updates relations to concrete products.
     * - Executes ProductListPreUpdatePluginInterface plugin stack before save.
     * - Returns MessageTransfers in messages property to notify about changes that have been made to Product List.
     * - Returns true isSuccess property if saving was successful.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function updateProductList(ProductListTransfer $productListTransfer): ProductListResponseTransfer;

    /**
     * Specification:
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Deletes Product List.
     * - Deletes relations to categories.
     * - Deletes relations to concrete products.
     *
     * @api
     *
     * @deprecated Use ProductListFacadeInterface::removeProductList() instead.
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return void
     */
    public function deleteProductList(ProductListTransfer $productListTransfer): void;

    /**
     * Specification:
     * - Finds a Product List by ProductListTransfer::idProductList in the transfer.
     * - Deletes Product List.
     * - Deletes relations to categories.
     * - Deletes relations to concrete products.
     * - Executes ProductListDeletePreCheckPluginInterface plugin stack before delete.
     * - Returns ProductListResponseTransfer.
     * - ProductListResponseTransfer::isSuccessful indicates operation success.
     * - ProductListResponseTransfer::messages contains error messages if deletion was not performed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListTransfer $productListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListResponseTransfer
     */
    public function removeProductList(ProductListTransfer $productListTransfer): ProductListResponseTransfer;

    /**
     * Specification:
     *  - Retrieves product blacklist ids by product abstract id.
     *
     * @api
     *
     * @deprecated Use ProductListFacadeInterface::getProductBlacklistIdsByIdProductAbstract() instead.
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * Specification:
     * - Retrieves product lists for product abstract ids and its categories.
     * - Returns product list where keys are product abstract IDs, values are arrays with product list ids by type.
     *
     * @api
     *
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    public function getProductAbstractListIdsByProductAbstractIds(array $productAbstractIds): array;

    /**
     * Specification:
     * - Retrieves product lists for product ids and its abstract products.
     * - Returns product list where keys are product concrete IDs, values are arrays with product list ids by type.
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return array
     */
    public function getProductListsIdsByProductIds(array $productIds): array;

    /**
     * Specification:
     *  - Retrieves product list IDs with type "blacklist".
     *  - Retrieves the product list IDs for product concretes related to the given product abstract ID.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * Specification:
     *  - Retrieves product whitelist ids by product abstract id.
     *
     * @api
     *
     * @deprecated Use ProductListFacadeInterface::getProductWhitelistIdsByIdProductAbstract() instead.
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

    /**
     * Specification:
     *  - Retrieves product list IDs with type "whitelist".
     *  - Retrieves the product list IDs for product concretes related to the given product abstract ID.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProductAbstract(int $idProductAbstract): array;

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
     *  - Retrieves product blacklist ids by product concrete id.
     *
     * @api
     *
     * @deprecated Use ProductListFacadeInterface::getProductBlacklistIdsByIdProduct() instead.
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductAbstractBlacklistIdsByIdProductConcrete(int $idProduct): array;

    /**
     * Specification:
     *  - Retrieves unique product list IDs with type "blacklist" for the given product concrete ID.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductBlacklistIdsByIdProduct(int $idProduct): array;

    /**
     * Specification:
     *  - Retrieves product whitelist ids by product concrete id.
     *
     * @api
     *
     * @deprecated Use ProductListFacadeInterface::getProductWhitelistIdsByIdProduct() instead.
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductAbstractWhitelistIdsByIdProductConcrete(int $idProduct): array;

    /**
     * Specification:
     *  - Retrieves unique product list IDs with type "whitelist" for the given product concrete ID.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return int[]
     */
    public function getProductWhitelistIdsByIdProduct(int $idProduct): array;

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

    /**
     * Specification:
     *  - Finds product concrete ids by product list ids.
     *
     * @api
     *
     * @param int[] $productListIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductListIds(array $productListIds): array;
}
