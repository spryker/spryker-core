<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository;

use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;

interface ProductImageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSet
     */
    public function getProductImageSetEntity(
        ProductImageSetTransfer $productImageSetTransfer
    ): SpyProductImageSet;

    /**
     * @param \Generated\Shared\Transfer\ProductImageTransfer $productImageTransfer
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImage
     */
    public function getProductImageEntity(ProductImageTransfer $productImageTransfer): SpyProductImage;

    /**
     * @param int $productImageSetId
     * @param int $productImageId
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage
     */
    public function getProductImageSetToProductImageRelationEntity(
        int $productImageSetId,
        int $productImageId
    ): SpyProductImageSetToProductImage;
}
