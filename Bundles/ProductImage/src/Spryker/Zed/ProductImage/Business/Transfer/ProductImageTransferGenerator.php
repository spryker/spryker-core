<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Transfer;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductImage\Dependency\Facade\ProductImageToLocaleInterface;

class ProductImageTransferGenerator implements ProductImageTransferGeneratorInterface
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
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage $productImageEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function convertProductImage(SpyProductImage $productImageEntity)
    {
        $productImageTransfer = (new ProductImageTransfer())
            ->fromArray($productImageEntity->toArray(), true);

        return $productImageTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage[]|\Propel\Runtime\Collection\ObjectCollection $productImageEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[]
     */
    public function convertProductImageCollection(ObjectCollection $productImageEntityCollection)
    {
        $transferList = [];
        foreach ($productImageEntityCollection as $productImageEntity) {
            $productImageTransfer = $this->convertProductImage($productImageEntity);
            $productImageTransfer->setSortOrder(
                (int)$productImageEntity->getSpyProductImageSetToProductImages()->getFirst()->getSortOrder()
            ); //getFirst since it's many to many while from this side it should be one to many

            $transferList[] = $productImageTransfer;
        }

        return $transferList;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function convertProductImageSet(SpyProductImageSet $productImageSetEntity)
    {
        $productImageSetTransfer = (new ProductImageSetTransfer())
            ->fromArray($productImageSetEntity->toArray(), true);

        $fkLocale = (int)$productImageSetEntity->getFkLocale();
        if ($fkLocale > 0) {
            $localeTransfer = $this->localeFacade->getLocaleById($fkLocale);
            $productImageSetTransfer->setLocale($localeTransfer);
        }

        $productImageSetTransfer->setIdProductAbstract($productImageSetEntity->getFkProductAbstract());
        $productImageSetTransfer->setIdProduct($productImageSetEntity->getFkProduct());

        $criteria = new Criteria();
        $criteria->addDescendingOrderByColumn(SpyProductImageSetToProductImageTableMap::COL_SORT_ORDER);

        $imageEntityCollection = [];
        foreach ($productImageSetEntity->getSpyProductImageSetToProductImagesJoinSpyProductImage($criteria) as $entity) {
            $imageEntityCollection[] = $entity->getSpyProductImage();
        }

        $objectCollection = new ObjectCollection($imageEntityCollection);
        $imageTransferCollection = $this->convertProductImageCollection($objectCollection);
        $productImageSetTransfer->setProductImages(new \ArrayObject($imageTransferCollection));

        return $productImageSetTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage[]|\Propel\Runtime\Collection\ObjectCollection $productImageSetEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function convertProductImageSetCollection(ObjectCollection $productImageSetEntityCollection)
    {
        $transferList = [];
        foreach ($productImageSetEntityCollection as $productImageSetEntity) {
            $transferList[] = $this->convertProductImageSet($productImageSetEntity);
        }

        return $transferList;
    }

}
