<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

interface TaxProductStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract[]
     */
    public function findProductAbstractEntitiesByProductAbstractIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findTaxProductStorageEntities(array $productAbstractIds, ?string $keyColumn = null): array;

    /**
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findAllTaxProductStorageEntities(): array;
}
