<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

interface ProductImageStorageRepositoryInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductLocalizedAttributesEntityTransfer[]
     */
    public function getProductLocalizedAttributesWithProductByIdProductIn(array $productIds): array;

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByIdProductIn(array $productIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByIdAbstractProductIn(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getDefaultAbstractProductImageSetsByIdAbstractProductIn(array $productAbstractIds): array;
}
