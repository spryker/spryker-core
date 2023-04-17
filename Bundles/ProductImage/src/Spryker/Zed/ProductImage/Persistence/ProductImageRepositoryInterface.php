<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

use Generated\Shared\Transfer\ProductImageFilterTransfer;
use Generated\Shared\Transfer\ProductImageSetCollectionTransfer;
use Generated\Shared\Transfer\ProductImageSetCriteriaTransfer;

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
     * @param list<int> $productSetIds
     *
     * @return array<int, list<\Generated\Shared\Transfer\ProductImageTransfer>>
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

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetCollectionTransfer
     */
    public function getConcreteProductImageSetCollection(
        ProductImageSetCriteriaTransfer $productImageSetCriteriaTransfer
    ): ProductImageSetCollectionTransfer;
}
