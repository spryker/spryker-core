<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Persistence;

interface ProductImageRepositoryInterface
{
    /**
     * @param int[] $productIds
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    public function getProductImagesSetTransfersByProductIdsAndIdLocale(array $productIds, int $idLocale): array;

    /**
     * @param int[] $productSetIds
     *
     * @return \Generated\Shared\Transfer\ProductImageTransfer[][]
     */
    public function getProductImagesByProductSetIds(array $productSetIds): array;

    /**
     * @param int[] $productImageIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageIds(array $productImageIds): array;

    /**
     * @param int[] $productImageSetIds
     *
     * @return int[]
     */
    public function getProductConcreteIdsByProductImageSetIds(array $productImageSetIds): array;
}
