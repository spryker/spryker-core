<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStoragePersistenceFactory getFactory()
 */
class TaxStorageRepository extends AbstractRepository implements TaxStorageRepositoryInterface
{
    /**
     * @module Tax
     *
     * @param int[] $taxRateIds
     *
     * @return int[]
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): array
    {
        $taxSetIds = $this->getFactory()
            ->getTaxSetQuery()
            ->useSpyTaxSetTaxQuery()
                ->useSpyTaxRateQuery()
                    ->filterByIdTaxRate_In($taxRateIds)
                ->endUse()
            ->endUse()
            ->select(SpyTaxSetTableMap::COL_ID_TAX_SET)
            ->groupBy(SpyTaxSetTableMap::COL_ID_TAX_SET)
            ->find()
            ->toArray();

        return $taxSetIds;
    }

    /**
     * @module Tax
     *
     * @param int[] $taxSetIds
     *
     * @return \Generated\Shared\Transfer\TaxSetStorageTransfer[]
     */
    public function findTaxSetsByIds(array $taxSetIds): array
    {
        $spyTaxSets = $this->getFactory()
            ->getTaxSetQuery()
            ->filterByIdTaxSet_In($taxSetIds)
            ->find()
            ->getArrayCopy();

        if (count($spyTaxSets) === 0) {
            return [];
        }

        return $this->getFactory()
            ->createTaxStorageMapper()
            ->mapSpyTaxSetsToTaxSetStorageTransfers($spyTaxSets);
    }

    /**
     * @param int[] $taxSetIds
     *
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getSynchronizationDataTransfersFromTaxSetStoragesByIdTaxSets(array $taxSetIds): array
    {
        $spyTaxSetStorage = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->filterByFkTaxSet_In($taxSetIds)
            ->find()
            ->getArrayCopy();

        if (count($spyTaxSetStorage) === 0) {
            return [];
        }

        return $this->getFactory()
            ->createTaxStorageMapper()
            ->mapSpyTaxSetStoragesToSynchronizationDataTransfer($spyTaxSetStorage);
    }

    /**
     * @return \Generated\Shared\Transfer\SynchronizationDataTransfer[]
     */
    public function getAllSynchronizationDataTransfersFromTaxSetStorages(): array
    {
        $spyTaxSetStorage = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->find()
            ->getArrayCopy();

        return $this->getFactory()
            ->createTaxStorageMapper()
            ->mapSpyTaxSetStoragesToSynchronizationDataTransfer($spyTaxSetStorage);
    }
}
