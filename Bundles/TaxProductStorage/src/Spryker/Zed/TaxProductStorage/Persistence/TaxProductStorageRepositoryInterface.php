<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage;

interface TaxProductStorageRepositoryInterface
{
    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer[]
     */
    public function getTaxProductTransferFromProductAbstractByIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findTaxProductStorageEntitiesByProductAbstractIdsIndexedByKeyColumn(array $productAbstractIds, ?string $keyColumn = null): array;

    /**
     * @param int $productAbstractId
     *
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage
     */
    public function findOrCreateTaxProductStorageByProductAbstractId(int $productAbstractId): SpyTaxProductStorage;

    /**
     * @return \Orm\Zed\TaxProductStorage\Persistence\SpyTaxProductStorage[]
     */
    public function findAllTaxProductStorageEntities(): array;
}
