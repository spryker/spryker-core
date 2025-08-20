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
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductImage\Business\ProductImageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductImage\Persistence\ProductImageRepositoryInterface getRepository()
 */
class ProductImageFacade extends AbstractFacade implements ProductImageFacadeInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductAbstractId($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->getProductImagesSetCollectionByProductAbstractId($idProductAbstract);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
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
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetCollectionByProductIdForCurrentLocale(int $idProduct): array
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->getProductImagesSetCollectionByProductIdForCurrentLocale($idProduct);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductImage\Business\ProductImageFacade::expandProductConcreteTransfersWithImageSets()} instead.
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
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcreteTransfersWithImageSets(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createProductImageReader()
            ->expandProductConcreteTransfersWithImageSets($productConcreteTransfers);
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
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
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
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
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
     * @deprecated Use {@link \Spryker\Zed\ProductImage\Business\ProductImageFacade::getConcreteProductImageSetCollection()} instead.
     *
     * @param array<int> $productIds
     * @param string $productImageSetName
     *
     * @return array<array<\Generated\Shared\Transfer\ProductImageTransfer>>
     */
    public function getProductImagesByProductIdsAndProductImageSetName(array $productIds, string $productImageSetName): array
    {
        return $this->getFactory()
            ->createProductImageBulkReader()
            ->getProductImagesByProductIdsAndProductImageSetName($productIds, $productImageSetName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageFilterTransfer $productImageFilterTransfer
     *
     * @return array<int>
     */
    public function getProductConcreteIds(ProductImageFilterTransfer $productImageFilterTransfer): array
    {
        return $this->getRepository()->getProductConcreteIds($productImageFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer> $productImageSetTransfers
     * @param string $localeName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function resolveProductImageSetsForLocale(ArrayObject $productImageSetTransfers, string $localeName): ArrayObject
    {
        return $this->getFactory()
            ->createProductImageSetResolver()
            ->resolveProductImageSetsForLocale($productImageSetTransfers, $localeName);
    }

    /**
     * {@inheritDoc}
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
    ): ProductConcreteTransfer {
        return $this->getFactory()
            ->createProductImageSetResolver()
            ->mergeProductAbstractImagesIntoProductConcrete($productConcreteTransfer, $productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getConcreteProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer {
        return $this->getFactory()
            ->createProductImageSetReader()
            ->getConcreteProductImageSetCollection($productImageSetCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getAbstractProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer {
        return $this->getFactory()
            ->createProductImageSetReader()
            ->getAbstractProductImageSetCollection($productImageSetCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated This method will be removed in the next major version. Product image alternative text will be enabled by default.
     *
     * @return bool
     */
    public function isProductImageAlternativeTextEnabled(): bool
    {
        return $this->getFactory()->getConfig()->isProductImageAlternativeTextEnabled();
    }
}
