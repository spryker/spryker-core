<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Transfer;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface;

class ProductImageTransferMapper implements ProductImageTransferMapperInterface
{
    /**
     * @var \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface $localeFacade
     */
    public function __construct(ProductImageToLocaleInterface $localeFacade)
    {
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $productImageSetEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function mapProductImageSetCollection(ObjectCollection $productImageSetEntityCollection)
    {
        $transferList = [];
        foreach ($productImageSetEntityCollection as $productImageSetEntity) {
            $transferList[] = $this->mapProductImageSet($productImageSetEntity);
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function mapProductImageSet(SpyProductImageSet $productImageSetEntity)
    {
        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->fromArray($productImageSetEntity->toArray(), true)
            ->setIdProduct($productImageSetEntity->getFkProduct())
            ->setIdProductAbstract($productImageSetEntity->getFkProductAbstract());

        $this->setProductImageSetLocale($productImageSetEntity, $productImageSetTransfer);
        $this->setProductImages($productImageSetEntity, $productImageSetTransfer);

        return $productImageSetTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage[]|\Propel\Runtime\Collection\ObjectCollection $productImageEntityCollection
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[]
     */
    public function mapProductImageCollection(ObjectCollection $productImageEntityCollection, SpyProductImageSet $productImageSetEntity)
    {
        $transferList = [];
        foreach ($productImageEntityCollection as $productImageEntity) {
            $productImageTransfer = $this->mapProductImage($productImageEntity);

            $productImageSetToProductImageEntity = $this->getProductImageSetToProductImageEntity($productImageSetEntity, $productImageEntity);

            $productImageTransfer->setSortOrder((int)$productImageSetToProductImageEntity->getSortOrder());

            $transferList[] = $productImageTransfer;
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage $productImageEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function mapProductImage(SpyProductImage $productImageEntity)
    {
        $productImageTransfer = (new ProductImageTransfer())
            ->fromArray($productImageEntity->toArray(), true);

        return $productImageTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage $productImageEntity
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    protected function getProductImageSetToProductImageEntity(SpyProductImageSet $productImageSetEntity, SpyProductImage $productImageEntity)
    {
        $criteria = new Criteria();
        $criteria->add(SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET, $productImageSetEntity->getIdProductImageSet());

        $productImageSetToProductImageEntity = $productImageEntity
            ->getSpyProductImageSetToProductImages($criteria)
            ->getFirst();

        return $productImageSetToProductImageEntity;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    protected function setProductImageSetLocale(SpyProductImageSet $productImageSetEntity, ProductImageSetTransfer $productImageSetTransfer)
    {
        $fkLocale = (int)$productImageSetEntity->getFkLocale();
        if ($fkLocale > 0) {
            $localeTransfer = $this->localeFacade->getLocaleById($fkLocale);
            $productImageSetTransfer->setLocale($localeTransfer);
        }
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return void
     */
    protected function setProductImages(SpyProductImageSet $productImageSetEntity, ProductImageSetTransfer $productImageSetTransfer)
    {
        $criteria = new Criteria();
        $criteria->addAscendingOrderByColumn(SpyProductImageSetToProductImageTableMap::COL_SORT_ORDER);

        $imageEntityCollection = [];
        foreach ($productImageSetEntity->getSpyProductImageSetToProductImagesJoinSpyProductImage($criteria) as $entity) {
            $imageEntityCollection[] = $entity->getSpyProductImage();
        }

        $imageTransferCollection = $this->mapProductImageCollection(new ObjectCollection($imageEntityCollection), $productImageSetEntity);
        $productImageSetTransfer->setProductImages(new ArrayObject($imageTransferCollection));
    }
}
