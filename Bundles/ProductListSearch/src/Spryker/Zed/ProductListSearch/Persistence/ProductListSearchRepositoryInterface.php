<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Persistence;

interface ProductListSearchRepositoryInterface
{
    /**
     * @return int
     */
    public function getValueForWhitelistType(): int;

    /**
     * @return int
     */
    public function getValueForBlacklistType(): int;

    /**
     * @param array<int> $concreteIds
     *
     * @return array<int>
     */
    public function getProductAbstractIdsByConcreteIds(array $concreteIds): array;

    /**
     * @param array $categoryIds
     *
     * @return array
     */
    public function getProductAbstractIdsByCategoryIds(array $categoryIds): array;

    /**
     * @module ProductCategory
     *
     * @param array<int, int> $categoryIdsTimestampMap
     *
     * @return array<int, int>
     */
    public function getProductAbstractIdsTimestampMap(array $categoryIdsTimestampMap): array;

    /**
     * @uses SpyProductQuery
     *
     * @param array<int, int> $concreteIdsTimestampMap
     *
     * @return array<int, int>
     */
    public function getProductAbstractIdsTimestampMapByConcreteIds(array $concreteIdsTimestampMap = []): array;
}
