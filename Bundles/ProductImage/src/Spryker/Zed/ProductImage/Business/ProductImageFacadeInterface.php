<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageFilterTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

interface ProductImageFacadeInterface
{
    /**
     * Specification:
     * - Creates a new product image entity or updates an existing one if the ID is provided and the entity already exists.
     * - Returns a ProductImageTransfer with the ID of the persisted entity.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function saveProductImage(ProductImageTransfer $productImageTransfer);

    /**
     * Specification:
     * - Creates a new product image set entity or updates an existing one if the ID is provided and the entity already exists.
     * - Creates new product image entities or update existing ones if their ID is provided and the entities already exists.
     * - Returns a ProductImageSetTransfer with the IDs of the persisted entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function saveProductImageSet(ProductImageSetTransfer $productImageSetTransfer);

    /**
     * Specification:
     * - Returns all product image sets from database for the given abstract product id.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract);

    /**
     * Specification:
     * - Returns all product image sets from database for the given concrete product id.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductId($idProduct);

    /**
     * Specification:
     * - Returns all product image sets from database for the given concrete product id and current locale.
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductIdForCurrentLocale(int $idProduct): array;

    /**
     * Specification:
     * - Persists all provided image sets to database for the given abstract product.
     * - Returns ProductAbstractTransfer along with the data from the persisted ProductImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function createProductAbstractImageSetCollection(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Persists all provided image sets to database for the given abstract product.
     * - Returns ProductAbstractTransfer along with the data from the persisted ProductImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function updateProductAbstractImageSetCollection(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Expands the ProductAbstractTransfer with the product's image sets from database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expandProductAbstractWithImageSets(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * Specification:
     * - Persists all provided image sets to database for the given concrete product.
     * - Returns ProductConcreteTransfer along with the data from the persisted ProductImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function createProductConcreteImageSetCollection(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Persists all provided image sets to database for the given concrete product.
     * - Returns ProductConcreteTransfer along with the data from the persisted ProductImageSetTransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function updateProductConcreteImageSetCollection(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Expands the ProductConcreteTransfer with the product's image sets from database.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface::expandProductConcreteTransfersWithImageSets()} instead.
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithImageSets(ProductConcreteTransfer $productConcreteTransfer);

    /**
     * Specification:
     * - Expands transfers of product concrete with the product's image sets from database.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithImageSets(array $productConcreteTransfers): array;

    /**
     * Specification:
     * - Deletes a ProductImageSet row from database
     * - Deletes orphan ProductImages and relations to a ProductImageSet
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    public function deleteProductImageSet(ProductImageSetTransfer $productImageSetTransfer);

    /**
     * Specification:
     * - Returns merged image sets for abstract product with the following inheritance: Abstract Default > Abstract Localized
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getCombinedAbstractImageSets($idProductAbstract, $idLocale);

    /**
     * Specification:
     * - Returns merged image sets for concrete product with the following inheritance:
     *  Abstract Default > Abstract Localized > Concrete Default > Concrete Localized
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getCombinedConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale);

    /**
     * Specification:
     * - Returns a product image set from database for the given ID.
     *
     * @api
     *
     * @param int $idProductImageSet
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|null
     */
    public function findProductImageSetById($idProductImageSet);

    /**
     * Specification:
     * - Returns collection of ProductImageTransfers indexed by product ids.
     * - Fetched images by array of product ids and product image set name.
     * - Images in image sets are sorted by `SpyProductImageSetToProductImage.sortOrder` column in ascending order.
     * - If there is no image set with desired name, returns images from the first image set.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductImage\Business\ProductImageFacadeInterface::getConcreteProductImageSetCollection()} instead.
     *
     * @param array<int> $productIds
     * @param string $productImageSetName
     *
     * @return array<array<\Generated\Shared\Transfer\ProductImageTransfer>>
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array;

    /**
     * Specification:
     * - Returns product concrete ids by provided filter criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageFilterTransfer $productImageFilterTransfer
     *
     * @return array<int>
     */
    public function getProductConcreteIds(ProductImageFilterTransfer $productImageFilterTransfer): array;

    /**
     * Specification:
     * - Merges localized product image sets by name.
     * - Merges default product image sets by name.
     * - Merges resulting localized and default product image sets together by name.
     * - If localized and default product image sets have the same names, localized one wins.
     * - Returns resulting product image sets.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param string $localeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function resolveProductImageSetsForLocale(ArrayObject $productImageSetTransfers, string $localeName): ArrayObject;

    /**
     * Specification:
     * - Merges image data from ProductAbstractTransfer into ProductConcreteTransfer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function mergeProductAbstractImageSetsIntoProductConcrete(
        ProductConcreteTransfer $productConcreteTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductConcreteTransfer;

    /**
     * Specification:
     * - Retrieves concrete product image set entities filtered by criteria.
     * - Retrieves ONLY product image sets with defined `fk_product` field inside `spy_product_image_set` table.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.productConcreteIds` to filter product image sets by concrete product IDs.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.productConcreteIds` to filter product image sets by names.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.skus` to filter productImageSets by product skus.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.localeNames` to filter product image sets by locale names.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.localeIds` to filter product image sets by locale IDs.
     * - Uses `ProductImageSetCriteriaTransfer.sort.field` to set the `order by` field.
     * - Uses `ProductImageSetCriteriaTransfer.sort.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `ProductImageSetCriteriaTransfer.pagination.{limit, offset}` to paginate result with limit and offset.
     * - Uses `ProductImageSetCriteriaTransfer.pagination.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - If `ProductImageSetCriteriaTransfer.addFallbackLocale` is set to true - fallback locale will be retrieved.
     * - Returns `ProductImageSetCollectionTransfer` filled with found product image sets.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getConcreteProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer;

    /**
     * Specification:
     * - Retrieves abstract product image set entities filtered by criteria.
     * - Retrieves ONLY product image sets with defined `fk_product_abstract` field inside `spy_product_image_set` table.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.productAbstractIds` to filter product image sets by abstract product IDs.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.names` to filter product image sets by names.
     * - Uses `ProductImageSetCriteriaTransfer.productImageSetConditions.localeIds` to filter product image sets by locale IDs.
     * - Uses `ProductImageSetCriteriaTransfer.sort.field` to set the `order by` field.
     * - Uses `ProductImageSetCriteriaTransfer.sort.isAscending` to set ascending order otherwise will be used descending order.
     * - Uses `ProductImageSetCriteriaTransfer.pagination.{limit, offset}` to paginate result with limit and offset.
     * - Uses `ProductImageSetCriteriaTransfer.pagination.{page, maxPerPage}` to paginate result with page and maxPerPage.
     * - If `ProductImageSetCriteriaTransfer.addFallbackLocale` is set to true - fallback locale will be retrieved.
     * - Returns `ProductImageSetCollectionTransfer` filled with found product image sets.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getAbstractProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer;

    /**
     * Specification:
     * - Checks if image alternative text feature is enabled.
     * - Gets the value from module configuration.
     *
     * @api
     *
     * @deprecated This method will be removed in the next major version. Product image alternative text will be enabled by default.
     *
     * @return bool
     */
    public function isProductImageAlternativeTextEnabled(): bool;
}
