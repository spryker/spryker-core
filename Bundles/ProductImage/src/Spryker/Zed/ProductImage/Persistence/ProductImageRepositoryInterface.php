<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Generated\Shared\Transfer\ProductImageFilterTransfer;

interface ProductImageRepositoryInterface
{
    /**
     * @param array<int> $productIds
     * @param int $idLocale
     *
     * @return array<\Generated\Shared\Transfer\ProductImageSetTransfer>
     */
    public function getProductImagesSetTransfersByProductIdsAndIdLocale(array $productIds, int $idLocale): array;

    /**
     * @param array<int> $productSetIds
     *
     * @return array<array<\Generated\Shared\Transfer\ProductImageTransfer>>
     */
    public function getProductImagesByProductSetIds(array $productSetIds): array;

    /**
     * @param \Generated\Shared\Transfer\ProductImageFilterTransfer $productImageFilterTransfer
     *
     * @return array<int>
     */
    public function getProductConcreteIds(ProductImageFilterTransfer $productImageFilterTransfer): array;

    /**
     * Result formats:
     * [
     *     $idProduct => [ProductImageSet, ...],
     *     ...,
     * ]
     *
     * @param array<int> $productIds
     *
     * @return array<array<\Orm\Zed\ProductImage\Persistence\SpyProductImageSet>>
     */
    public function getProductImageSetsGroupedByIdProduct(array $productIds): array;
}
