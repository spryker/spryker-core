<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Spryker\Zed\ProductImage\Business\Exception\InvalidProductImageSetException;
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
        $productImageEntity = $this->productImageContainer
            ->queryProductImage()
            ->filterByIdProductImage($productImageTransfer->getIdProductImage())
            ->findOneOrCreate();

        $productImageEntity->fromArray($productImageTransfer->modifiedToArray(), true);
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
        $this->assertProductIsAssigned($productImageSetTransfer);

        $this->productImageContainer->getConnection()->beginTransaction();

        $productImageSetEntity = $this->productImageContainer
            ->queryProductImageSet()
            ->filterByIdProductImageSet($productImageSetTransfer->getIdProductImageSet())
            ->findOneOrCreate();

        $productImageSetEntity = $this->mapProductImageSetEntity($productImageSetEntity, $productImageSetTransfer);
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
    protected function mapProductImageSetEntity(SpyProductImageSet $productImageSetEntity, ProductImageSetTransfer $productImageSetTransfer)
    {
        $productImageSetEntity->fromArray($productImageSetTransfer->modifiedToArray(), true);
        $productImageSetEntity->setFkProductAbstract($productImageSetTransfer->getIdProductAbstract());
        $productImageSetEntity->setFkProduct($productImageSetTransfer->getIdProduct());

        if ($productImageSetTransfer->getLocale()) {
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
        foreach ($productImageSetTransfer->getProductImages() as $imageTransfer) {
            $imageTransfer = $this->persistProductImage($imageTransfer);

            $this->persistProductImageRelation(
                $productImageSetTransfer->requireIdProductImageSet()->getIdProductImageSet(),
                $imageTransfer->getIdProductImage(),
                $imageTransfer->getSortOrder()
            );
        }

        return $productImageSetTransfer;
    }

    /**
     * @param int $idProductImageSet
     * @param int $idProductImage
     * @param int|null $sortOrder
     *
     * @return int
     */
    public function persistProductImageRelation($idProductImageSet, $idProductImage, $sortOrder = null)
    {
        $productImageRelationEntity = $this->productImageContainer
            ->queryProductImageSetToProductImage()
            ->filterByFkProductImageSet($idProductImageSet)
            ->filterByFkProductImage($idProductImage)
            ->findOneOrCreate();

        $productImageRelationEntity->setSortOrder((int)$sortOrder);
        $productImageRelationEntity->save();

        return $productImageRelationEntity->getIdProductImageSetToProductImage();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function createProductAbstractImageSetCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($productAbstractTransfer->getImageSets() as $imageSetTransfer) {
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
    public function updateProductAbstractImageSetCollection(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($productAbstractTransfer->getImageSets() as $imageSetTransfer) {
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
    public function createProductConcreteImageSetCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($productConcreteTransfer->getImageSets() as $imageSetTransfer) {
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
    public function updateProductConcreteImageSetCollection(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($productConcreteTransfer->getImageSets() as $imageSetTransfer) {
            $imageSetTransfer->setIdProduct(
                $productConcreteTransfer
                    ->requireIdProductConcrete()
                    ->getIdProductConcrete()
            );

            $this->updateProductImageSet($imageSetTransfer);
        }

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @throws \Spryker\Zed\ProductImage\Business\Exception\InvalidProductImageSetException
     *
     * @return void
     */
    protected function assertProductIsAssigned(ProductImageSetTransfer $productImageSetTransfer)
    {
        if ((int)$productImageSetTransfer->getIdProductAbstract() === 0 && (int)$productImageSetTransfer->getIdProduct() === 0) {
            throw new InvalidProductImageSetException('ImageSet has no product assigned.');
        }
    }

}
