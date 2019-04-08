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
     * @return array
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): array
    {
        $taxRateIds = $this->getFactory()
            ->createTaxSetQuery()
            ->select(SpyTaxSetTableMap::COL_ID_TAX_SET)
            ->useSpyTaxSetTaxQuery()
                ->useSpyTaxRateQuery()
                    ->filterByIdTaxRate_In($taxRateIds)
                ->endUse()
            ->endUse()
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
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSet[]
     */
    public function findTaxSetsByIds(array $taxSetIds): array
    {
        $spyTaxSets = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByIdTaxSet_In($taxSetIds)
            ->find()
            ->getArrayCopy();

        return $spyTaxSets;
    }

    /**
     * @param int[] $taxSetIds
     *
     * @return \Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[]
     */
    public function findTaxSetStoragesByIds(array $taxSetIds): array
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

    /**
     * @param int[] $taxSetIds
     *
     * @return void
     */
    public function deleteTaxSetStoragesByIds(array $taxSetIds): void
    {
        $this->getFactory()
            ->createTaxSetStorageQuery()
            ->filterByFkTaxSet_In($taxSetIds)
            ->delete();
    }
}
