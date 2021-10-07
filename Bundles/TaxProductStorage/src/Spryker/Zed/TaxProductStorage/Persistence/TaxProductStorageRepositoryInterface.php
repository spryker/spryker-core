<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

interface TaxProductStorageRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\TaxProductStorageTransfer>
     */
    public function getTaxProductTransfersFromProductAbstractsByIds(array $productAbstractIds): array;

    /**
     * @param array<int> $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersFromTaxProductStoragesByProductAbstractIds(array $productAbstractIds, ?string $keyColumn = null): array;

    /**
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getAllSynchronizationDataTransfersFromTaxProductStorages(): array;
}
