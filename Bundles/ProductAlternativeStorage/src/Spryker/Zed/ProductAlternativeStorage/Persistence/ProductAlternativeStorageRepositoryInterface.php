<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Persistence;

use Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer;

interface ProductAlternativeStorageRepositoryInterface
{
    /**
     * @param int[] $productIds
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeStorageEntityTransfer[]
     */
    public function findProductAlternativeStorageEntities(array $productIds): array;

    /**
     * @param int $idProduct
     *
     * @return string
     */
    public function findProductSkuById($idProduct): string;

    /**
     * @param int $idProduct
     *
     * @return string[]
     */
    public function findAbstractAlternativesIdsByConcreteProductId($idProduct): array;

    /**
     * @param int $idProduct
     *
     * @return string[]
     */
    public function findConcreteAlternativesIdsByConcreteProductId($idProduct): array;

    /**
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getIndexedProductConcreteIdToSkusByProductIds(array $productIds): array;

    /**
     * @param int[] $productIds
     *
     * @return string[]
     */
    public function getIndexedProductAbstractIdToSkusByProductIds(array $productIds): array;

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\SpyProductReplacementStorageEntityTransfer|null
     */
    public function findProductReplacementStorageEntitiesBySku(string $sku): ?SpyProductReplacementStorageEntityTransfer;

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getReplacementsByAbstractProductId(int $idProductAbstract): array;

    /**
     * @param int $idProductConcrete
     *
     * @return int[]
     */
    public function getReplacementsByConcreteProductId(int $idProductConcrete): array;
}
