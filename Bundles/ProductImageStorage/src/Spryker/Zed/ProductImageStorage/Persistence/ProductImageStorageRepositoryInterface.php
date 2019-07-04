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
     * @return array
     */
    public function getProductLocalizedAttributesWithProductByIdProductIn(array $productIds): array;

    /**
     * @param int[] $productFks
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getProductImageSetsByFkProductIn(array $productFks): array;

    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getDefaultConcreteProductImageSetsByFkProductIn(array $productIds): array;

    /**
     * @param int[] $productAbstractFks
     *
     * @return array
     */
    public function getProductImageSetsByFkAbstractProductIn(array $productAbstractFks): array;

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer[]
     */
    public function getDefaultAbstractProductImageSetsByIdAbstractProductIn(array $productAbstractIds): array;
}
