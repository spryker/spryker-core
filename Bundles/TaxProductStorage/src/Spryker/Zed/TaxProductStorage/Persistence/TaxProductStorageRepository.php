<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\TaxProductStorage\Persistence\TaxProductStoragePersistenceFactory getFactory()
 */
class TaxProductStorageRepository extends AbstractRepository implements TaxProductStorageRepositoryInterface
{
    /**
     * @module Product
     *
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\TaxProductStorageTransfer[]
     */
    public function getTaxProductTransfersFromProductAbstractsByIds(array $productAbstractIds): array
    {
        if (count($productAbstractIds) === 0) {
            return [];
        }

        $spyProductAbstracts = $this->getFactory()
            ->getProductAbstractQuery()
            ->filterByIdProductAbstract_In($productAbstractIds)
            ->find()
            ->getArrayCopy();

        if (count($spyProductAbstracts) === 0) {
            return [];
        }

        return $this->getFactory()
            ->createTaxProductStorageMapper()
            ->mapSpyProductAbstractsToTaxProductStorageTransfers($spyProductAbstracts);
    }

    /**
     * @param int[] $productAbstractIds
     * @param string|null $keyColumn
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersFromTaxProductStoragesByProductAbstractIds(
        array $productAbstractIds,
        ?string $keyColumn = null
    ): array {
        if (count($productAbstractIds) === 0) {
            return [];
        }

        $spyTaxProductStorages = $this->getFactory()
            ->createTaxProductStorageQuery()
            ->filterByFkProductAbstract_In($productAbstractIds)
            ->find()
            ->getArrayCopy();

        return $this->getFactory()
            ->createTaxProductStorageMapper()
            ->mapSpyTaxProductStorageToSynchronizationDataTransfer($spyTaxProductStorages);
    }

    /**
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getAllSynchronizationDataTransfersFromTaxProductStorages(): array
    {
        $spyTaxProductStorages = $this->getFactory()
            ->createTaxProductStorageQuery()
            ->find()
            ->getArrayCopy();

        return $this->getFactory()
            ->createTaxProductStorageMapper()
            ->mapSpyTaxProductStorageToSynchronizationDataTransfer($spyTaxProductStorages);
    }
}
