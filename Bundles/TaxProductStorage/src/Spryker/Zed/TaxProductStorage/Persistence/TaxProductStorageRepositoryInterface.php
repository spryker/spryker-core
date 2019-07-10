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
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer[]
     */
    public function getTaxProductTransfersFromProductAbstractsByIds(array $productAbstractIds): array;

    /**
     * @param int[] $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersFromTaxProductStoragesByProductAbstractIds(array $productAbstractIds, ?string $keyColumn = null): array;

    /**
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getAllSynchronizationDataTransfersFromTaxProductStorages(): array;
}
