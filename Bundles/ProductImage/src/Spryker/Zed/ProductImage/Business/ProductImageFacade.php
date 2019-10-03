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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface getRepository()
 */
class ProductImageFacade extends AbstractFacade implements ProductImageFacadeInterface
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
    public function saveProductImage(ProductImageTransfer $productImageTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->saveProductImage($productImageTransfer);
    }

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
    public function saveProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->saveProductImageSet($productImageSetTransfer);
    }

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
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->getProductImagesSetCollectionByProductAbstractId($idProductAbstract);
    }

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
    public function getProductImagesSetCollectionByProductId($idProduct)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->getProductImagesSetCollectionByProductId($idProduct);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetCollectionByProductIdForCurrentLocale(int $idProduct): array
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->getProductImagesSetCollectionByProductIdForCurrentLocale($idProduct);
    }

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
    public function createProductAbstractImageSetCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->createProductAbstractImageSetCollection($productAbstractTransfer);
    }

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
    public function updateProductAbstractImageSetCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->updateProductAbstractImageSetCollection($productAbstractTransfer);
    }

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
    public function expandProductAbstractWithImageSets(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->expandProductAbstractWithImageSets($productAbstractTransfer);
    }

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
    public function createProductConcreteImageSetCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->createProductConcreteImageSetCollection($productConcreteTransfer);
    }

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
    public function updateProductConcreteImageSetCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductImageWriter()
            ->updateProductConcreteImageSetCollection($productConcreteTransfer);
    }

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
    public function expandProductConcreteWithImageSets(ProductConcreteTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->expandProductConcreteWithImageSets($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    public function deleteProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $this->getFactory()
            ->createProductImageWriter()
            ->deleteProductImageSet($productImageSetTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedAbstractImageSets($idProductAbstract, $idLocale)
    {
        return $this->getFactory()
            ->createProductImageSetCombiner()
            ->getCombinedAbstractImageSets($idProductAbstract, $idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getCombinedConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale)
    {
        return $this->getFactory()
            ->createProductImageSetCombiner()
            ->getCombinedConcreteImageSets($idProductConcrete, $idProductAbstract, $idLocale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductImageSet
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer|null
     */
    public function findProductImageSetById($idProductImageSet)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->findProductImagesSetCollectionById($idProductImageSet);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     * @param string $productImageSetName
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array
    {
        return $this->getFactory()
            ->createProductImageBulkReader()
            ->getProductImagesByProductIdsAndProductImageSetName($productIds, $productImageSetName);
    }
}
