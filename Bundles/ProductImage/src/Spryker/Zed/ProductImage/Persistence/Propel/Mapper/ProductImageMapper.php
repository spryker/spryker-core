<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Propel\Runtime\Collection\ObjectCollection;

class ProductImageMapper
{
    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet $productImageSetEntity
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer
     */
    public function mapProductImageSetEntityToProductImageSetTransfer(
        SpyProductImageSet $productImageSetEntity,
        ProductImageSetTransfer $productImageSetTransfer
    ): ProductImageSetTransfer {
        $productImageSetTransfer = $productImageSetTransfer->fromArray($productImageSetEntity->toArray(), true);
        $productImageSetTransfer->setIdProduct($productImageSetEntity->getFkProduct());

        return $productImageSetTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImage $productImageEntity
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer
     */
    public function mapProductImageEntityToProductImageTransfer(SpyProductImage $productImageEntity, ProductImageTransfer $productImageTransfer): ProductImageTransfer
    {
        $productImageTransfer = $productImageTransfer->fromArray($productImageEntity->toArray(), true);

        return $productImageTransfer;
    }

    /**
     * @param \Orm\Zed\ProductImage\Persistence\SpyProductImageSet[]|\Propel\Runtime\Collection\ObjectCollection $productImageSetEntities
     * @param \Generated\Shared\Transfer\ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function mapProductImageSetEntitiesToProductImageSetCollectionTransfer(
        ObjectCollection $productImageSetEntities,
        ProductImageSetCollectionTransfer $productImageSetCollectionTransfer
    ): ProductImageSetCollectionTransfer {
        foreach ($productImageSetEntities as $productImageSetEntity) {
            $productImageSetTransfer = $this->mapProductImageSetEntityToProductImageSetTransfer(
                $productImageSetEntity,
                new ProductImageSetTransfer()
            );

            foreach ($productImageSetEntity->getSpyProductImageSetToProductImages() as $productImageSetToProductImageEntity) {
                $productImageTransfer = $this->mapProductImageEntityToProductImageTransfer(
                    $productImageSetToProductImageEntity->getSpyProductImage(),
                    new ProductImageTransfer()
                );
                $productImageSetTransfer->addProductImage($productImageTransfer);
            }

            $productImageSetCollectionTransfer->addProductImageSet($productImageSetTransfer);
        }

        return $productImageSetCollectionTransfer;
    }
}
