<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

interface TaxStorageRepositoryInterface
{
    /**
     * @param array<int> $taxRateIds
     *
     * @return array<int>
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): array;

    /**
     * @param array<int> $taxSetIds
     *
     * @return array<\Generated\Shared\Transfer\TaxSetStorageTransfer>
     */
    public function findTaxSetsByIds(array $taxSetIds): array;

    /**
     * @param array<int> $taxSetIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersFromTaxSetStoragesByIdTaxSets(array $taxSetIds): array;

    /**
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getAllSynchronizationDataTransfersFromTaxSetStorages(): array;
}
