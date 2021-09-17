<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Persistence;

interface ProductImageStorageRepositoryInterface
{
    /**
     * @param array<int> $productIds
     *
     * @return array
     */
    public function getProductLocalizedAttributesWithProductByIdProductIn(array $productIds): array;

    /**
     * @param array<int> $productFks
     *
     * @return array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer>
     */
    public function getProductImageSetsByFkProductIn(array $productFks): array;

    /**
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer>
     */
    public function getDefaultConcreteProductImageSetsByFkProductIn(array $productIds): array;

    /**
     * @param array<int> $productAbstractFks
     *
     * @return array
     */
    public function getProductImageSetsByFkAbstractProductIn(array $productAbstractFks): array;

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\SpyProductImageSetEntityTransfer>
     */
    public function getDefaultAbstractProductImageSetsByIdAbstractProductIn(array $productAbstractIds): array;
}
