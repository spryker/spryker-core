<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;

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
}
