<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;

class Writer implements WriterInterface
{

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageContainer;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     */
    public function __construct(ProductImageQueryContainerInterface $productImageContainer)
    {
        $this->productImageContainer = $productImageContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function createProductImage(ProductImageTransfer $productImageTransfer)
    {
        return $this->persistProductImage($productImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function updateProductImage(ProductImageTransfer $productImageTransfer)
    {
        return $this->persistProductImage($productImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function persistProductImage(ProductImageTransfer $productImageTransfer)
    {
        $query = $this->productImageContainer
            ->queryProductImage()
            ->filterByIdProductImage($productImageTransfer->getIdProductImage());

        $productImageEntity = $query->findOne();
        if (!$productImageEntity) {
            $productImageEntity = new SpyProductImage();
        }

        $productImageEntity->fromArray($productImageTransfer->toArray());
        $productImageEntity->save();

        $productImageTransfer->setIdProductImage($productImageEntity->getIdProductImage());

        return $productImageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function createProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        return $this->persistProductImageSet($productImageSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function updateProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        return $this->persistProductImageSet($productImageSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function persistProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $this->productImageContainer->getConnection()->beginTransaction();

        $this->assertProductIsAssigned($productImageSetTransfer);

        $query = $this->productImageContainer
            ->queryProductImageSet()
            ->filterByIdProductImageSet($productImageSetTransfer->getIdProductImageSet());

        $productImageSetEntity = $query->findOne();
        if (!$productImageSetEntity) {
            $productImageSetEntity = new SpyProductImageSet();
        }

        $productImageSetEntity = $this->hydrateProductImageSet($productImageSetEntity, $productImageSetTransfer);
        $productImageSetEntity->save();

        $productImageSetTransfer->setIdProductImageSet(
            $productImageSetEntity->getIdProductImageSet()
        );

        $productImageSetTransfer = $this->persistProductImageSetCollection($productImageSetTransfer);

        $this->productImageContainer->getConnection()->commit();

        return $productImageSetTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    protected function hydrateProductImageSet(SpyProductImageSet $productImageSetEntity, ProductImageSetTransfer $productImageSetTransfer)
    {
        $productImageSetEntity->fromArray($productImageSetTransfer->toArray());
        $productImageSetEntity->setFkProductAbstract($productImageSetTransfer->getIdProductAbstract());
        $productImageSetEntity->setFkProduct($productImageSetTransfer->getIdProduct());

        if ($productImageSetTransfer->getLocale() instanceof LocaleTransfer) {
            $productImageSetEntity->setFkLocale($productImageSetTransfer->getLocale()->getIdLocale());
        }

        return $productImageSetEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    protected function persistProductImageSetCollection(ProductImageSetTransfer $productImageSetTransfer)
    {
        $updatedImageCollection = [];
        foreach ($productImageSetTransfer->getProductImages() as $imageTransfer) {
            $imageTransfer = $this->persistProductImage($imageTransfer);

            $this->persistProductImageRelation(
                $productImageSetTransfer->requireIdProductImageSet()->getIdProductImageSet(),
                $imageTransfer->getIdProductImage(),
                $imageTransfer->getSortOrder()
            );

            $updatedImageCollection[] = $imageTransfer;
        }

        $productImageSetTransfer->setProductImages(
            new \ArrayObject($updatedImageCollection)
        );

        return $productImageSetTransfer;
    }

    /**
     * @param int $idProductImageSet
     * @param int $idProductImage
     * @param int|null $sort_order
     *
     * @return int
     */
    public function persistProductImageRelation($idProductImageSet, $idProductImage, $sort_order = null)
    {
        $query = $this->productImageContainer
            ->queryProductImageSetToProductImage()
            ->filterByFkProductImageSet($idProductImageSet)
            ->filterByFkProductImage($idProductImage);

        $productImageRelationEntity = $query->findOneOrCreate();
        $productImageRelationEntity->setSortOrder((int)$sort_order);
        $productImageRelationEntity->save();

        $productImageRelationEntity->setIdProductImageSetToProductImage(
            $productImageRelationEntity->getIdProductImageSetToProductImage()
        );

        return $productImageRelationEntity->getIdProductImageSetToProductImage();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function runProductAbstractCreatePluginRun(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSetTransferCollection = $productAbstractTransfer->getImageSets();

        foreach ($imageSetTransferCollection as $imageSetTransfer) {
            $imageSetTransfer->setIdProductAbstract(
                $productAbstractTransfer
                    ->requireIdProductAbstract()
                    ->getIdProductAbstract()
            );

            $this->createProductImageSet($imageSetTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function runProductAbstractUpdatePlugin(ProductAbstractTransfer $productAbstractTransfer)
    {
        $imageSetTransferCollection = $productAbstractTransfer->getImageSets();

        foreach ($imageSetTransferCollection as $imageSetTransfer) {
            $imageSetTransfer->setIdProductAbstract(
                $productAbstractTransfer
                    ->requireIdProductAbstract()
                    ->getIdProductAbstract()
            );

            $this->updateProductImageSet($imageSetTransfer);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function runProductConcreteCreatePluginRun(ProductConcreteTransfer $productConcreteTransfer)
    {
        $imageSetTransferCollection = $productConcreteTransfer->getImageSets();

        foreach ($imageSetTransferCollection as $imageSetTransfer) {
            $imageSetTransfer->setIdProduct(
                $productConcreteTransfer
                    ->requireIdProductConcrete()
                    ->getIdProductConcrete()
            );

            $this->createProductImageSet($imageSetTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function runProductConcreteUpdatePlugin(ProductConcreteTransfer $productConcreteTransfer)
    {
        $imageSetTransferCollection = $productConcreteTransfer->getImageSets();

        foreach ($imageSetTransferCollection as $imageSetTransfer) {
            $imageSetTransfer->setIdProductAbstract(
                $productConcreteTransfer
                    ->requireIdProductConcrete()
                    ->getIdProductConcrete()
            );

            $this->updateProductImageSet($imageSetTransfer); //TODO add updateProductImageSet() and use the entry point
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function assertProductIsAssigned(ProductImageSetTransfer $productImageSetTransfer)
    {
        if ((int)$productImageSetTransfer->getIdProductAbstract() === 0 && (int)$productImageSetTransfer->getIdProduct()) {
            throw new \Exception('ImageSet has no product assigned');
        }
    }

}
