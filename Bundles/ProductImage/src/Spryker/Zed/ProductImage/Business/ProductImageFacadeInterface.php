<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
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
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
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
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
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
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteWithImageSets(ProductConcreteTransfer $productConcreteTransfer);

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
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
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
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
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
     * - If there is no image set with desired name, returns images from the first image set.
     *
     * @api
     *
     * @param int[] $productIds
     * @param string $productImageSetName
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array;
}
