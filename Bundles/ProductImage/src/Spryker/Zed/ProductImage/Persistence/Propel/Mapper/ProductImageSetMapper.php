<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Propel\Runtime\Collection\ObjectCollection;

class ProductImageSetMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductImage\Persistence\SpyProductImageSet> $productImageSetEntityCollection
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function mapConcreteProductImageSetEntityCollectionToProductImageSetCollectionTransfer(
        ObjectCollection $productImageSetEntityCollection,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): ProductImageSetCollectionTransfer {
        foreach ($productImageSetEntityCollection as $productImageSetEntity) {
            $productImageSetCollectionTransfer->addProductImageSet(
                $this->mapConcreteProductImageSetEntityToProductImageSetTransfer($productImageSetEntity, new ProductImageSetTransfer()),
            );
        }

        return $productImageSetCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductImage\Persistence\SpyProductImageSet> $productImageSetEntityCollection
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function mapAbstractProductImageSetEntityCollectionToProductImageSetCollectionTransfer(
        ObjectCollection $productImageSetEntityCollection,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): ProductImageSetCollectionTransfer {
        foreach ($productImageSetEntityCollection as $productImageSetEntity) {
            $productImageSetCollectionTransfer->addProductImageSet(
                $this->mapAbstractProductImageSetEntityToProductImageSetTransfer($productImageSetEntity, new ProductImageSetTransfer()),
            );
        }

        return $productImageSetCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    protected function mapConcreteProductImageSetEntityToProductImageSetTransfer(
        SpyProductImageSet $productImageSetEntity,
        ProductImageSetTransfer $productImageSetTransfer
    ): ProductImageSetTransfer {
        $productImageSetTransfer = $productImageSetTransfer->fromArray($productImageSetEntity->toArray(), true);
        $productImageSetTransfer
            ->setIdProduct($productImageSetEntity->getFkProduct())
            ->setSku($productImageSetEntity->getSpyProduct()->getSku());

        if ($productImageSetEntity->getFkLocale()) {
            $productImageSetTransfer->setLocale(
                (new LocaleTransfer())->fromArray($productImageSetEntity->getSpyLocale()->toArray(), true),
            );
        }

        return $productImageSetTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    protected function mapAbstractProductImageSetEntityToProductImageSetTransfer(
        SpyProductImageSet $productImageSetEntity,
        ProductImageSetTransfer $productImageSetTransfer
    ): ProductImageSetTransfer {
        $productImageSetTransfer = $productImageSetTransfer->fromArray($productImageSetEntity->toArray(), true);
        $productImageSetTransfer
            ->setIdProductAbstract($productImageSetEntity->getFkProductAbstract());

        return $productImageSetTransfer;
    }
}
