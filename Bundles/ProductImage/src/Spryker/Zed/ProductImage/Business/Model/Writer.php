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
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductImage\Business\Exception\InvalidProductImageSetException;
use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;

class Writer implements WriterInterface
{

    /**
     * @var \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface
     */
    protected $productImageQueryContainer;

    /**
     * @param \Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface $productImageContainer
     */
    public function __construct(ProductImageQueryContainerInterface $productImageContainer)
    {
        $this->productImageQueryContainer = $productImageContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function createProductImage(ProductImageTransfer $productImageTransfer)
    {
        return $this->saveProductImage($productImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function updateProductImage(ProductImageTransfer $productImageTransfer)
    {
        return $this->saveProductImage($productImageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function saveProductImage(ProductImageTransfer $productImageTransfer)
    {
        $productImageEntity = $this->productImageQueryContainer
            ->queryProductImage()
            ->filterByIdProductImage($productImageTransfer->getIdProductImage())
            ->findOneOrCreate();

        $productImageEntity->fromArray($productImageTransfer->toArray());
        $productImageEntity->save();

        $productImageTransfer->setIdProductImage($productImageEntity->getIdProductImage());

        return $productImageTransfer;
    }

    /**
     * @param ProductConcreteTransfer $productConcreteTransfer
     */
    protected function deleteMissingProductImageSetInProductConcrete(ProductConcreteTransfer $productConcreteTransfer)
    {
        $excludeIdProductImageSet = [];

        foreach ($productConcreteTransfer->getImageSets() as $productImageSetTransfer) {
            $excludeIdProductImageSet[] = $productImageSetTransfer->getIdProductImageSet();
        }

        $missingProductImageSets = $this->productImageQueryContainer
            ->queryImageSetByProductId($productConcreteTransfer->getIdProductConcrete(), $excludeIdProductImageSet)
            ->find()->toKeyIndex();

        $this->deleteProductImageSets($missingProductImageSets);
    }

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     */
    protected function deleteMissingProductImageSetInProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $excludeIdProductImageSet = [];

        foreach ($productAbstractTransfer->getImageSets() as $productImageSetTransfer) {
            $excludeIdProductImageSet[] = $productImageSetTransfer->getIdProductImageSet();
        }

        $missingProductImageSets = $this->productImageQueryContainer
            ->queryImageSetByProductAbstractId($productAbstractTransfer->getIdProductAbstract(), $excludeIdProductImageSet)
            ->find()->toKeyIndex();

        $this->deleteProductImageSets($missingProductImageSets);
    }

    /**
     * @param ProductImageSetTransfer $productImageSetTransfer
     */
    protected function deleteMissingProductImageInProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $excludeIdProductImage = [];

        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $excludeIdProductImage[] = $productImageTransfer->getIdProductImage();
        }

        $missingProductImageSeToProductImage = $this->productImageQueryContainer
            ->queryProductImageSetToProductImageByProductImageSetId($productImageSetTransfer->getIdProductImageSet(), $excludeIdProductImage)
            ->find();

        foreach ($missingProductImageSeToProductImage as $productImageSetToProductImage) {
            $this->deleteProductImageSetToProductImage($productImageSetToProductImage);
        }
    }

    /**
     * @param SpyProductImageSet[] $productImageSets
     */
    protected function deleteProductImageSets(array $productImageSets)
    {
        foreach ($productImageSets as $productImageSet) {
            foreach ($productImageSet->getSpyProductImageSetToProductImages() as $productImageSetToProductImage) {
                $this->deleteProductImageSetToProductImage($productImageSetToProductImage);
            }

            $productImageSet->delete();
        }
    }

    /**
     * @param SpyProductImageSetToProductImage $productImageSetToProductImage
     */
    protected function deleteProductImageSetToProductImage(SpyProductImageSetToProductImage $productImageSetToProductImage)
    {
        $productImage = $productImageSetToProductImage->getSpyProductImage();
        $productImage->removeSpyProductImageSetToProductImage($productImageSetToProductImage);
        $productImage->save();

        if ($productImage->getSpyProductImageSetToProductImages()->isEmpty()) {
            $productImage->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function createProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        return $this->saveProductImageSet($productImageSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function updateProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $this->deleteMissingProductImageInProductImageSet($productImageSetTransfer);
        return $this->saveProductImageSet($productImageSetTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function saveProductImageSet(ProductImageSetTransfer $productImageSetTransfer)
    {
        $this->assertProductIsAssigned($productImageSetTransfer);

        $this->productImageQueryContainer->getConnection()->beginTransaction();

        $productImageSetEntity = $this->productImageQueryContainer
            ->queryProductImageSet()
            ->filterByIdProductImageSet($productImageSetTransfer->getIdProductImageSet())
            ->findOneOrCreate();

        $productImageSetEntity = $this->mapProductImageSetEntity($productImageSetEntity, $productImageSetTransfer);
        $productImageSetEntity->save();

        $productImageSetTransfer->setIdProductImageSet(
            $productImageSetEntity->getIdProductImageSet()
        );

        $productImageSetTransfer = $this->persistProductImageSetCollection($productImageSetTransfer);

        $this->productImageQueryContainer->getConnection()->commit();

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
        $productImageSetEntity->fromArray($productImageSetTransfer->toArray());
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
            $imageTransfer = $this->saveProductImage($imageTransfer);

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
        $productImageRelationEntity = $this->productImageQueryContainer
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

        $this->deleteMissingProductImageSetInProductAbstract($productAbstractTransfer);

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

        $this->deleteMissingProductImageSetInProductConcrete($productConcreteTransfer);

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
