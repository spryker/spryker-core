<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Orm\Zed\Tax\Persistence\SpyTaxSet;
use Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage;
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
        $taxRateIds = $this->getFactory()
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

        return $taxRateIds;
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
        $taxStorageMapper = $this->getFactory()->createTaxStorageMapper();
        $spyTaxSets = $this->getFactory()
            ->getTaxSetQuery()
            ->filterByIdTaxSet_In($taxSetIds)
            ->find();

        if($spyTaxSets->isEmpty()){
            return [];
        }

        return $taxStorageMapper->mapSpyTaxSetsToTaxSetStorageTransfers($spyTaxSets);
    }



    /**
     * @param int[] $taxSetIds
     *
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[]
     */
    public function findTaxSetStoragesByIdTaxSetsIndexedByFkTaxSet(array $taxSetIds): array
    {
        $spyTaxSetStorage = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->filterByFkTaxSet_In($taxSetIds)
            ->find()
            ->toKeyIndex('FkTaxSet');

        return $spyTaxSetStorage;
    }

    /**
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[]
     */
    public function findAllTaxSetStorages(): array
    {
        $spyTaxSetStorage = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->find()
            ->getArrayCopy();

        return $spyTaxSetStorage;
    }
}
