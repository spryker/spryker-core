<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Persistence;

use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\TaxStorage\Persistence\TaxStoragePersistenceFactory getFactory()
 */
class TaxStorageRepository extends AbstractRepository implements TaxStorageRepositoryInterface
{
    /**
     * @param int[] $taxRateIds
     *
     * @return array
     */
    public function findTaxSetIdsByTaxRateIds(array $taxRateIds): iterable
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
     * @param int[] $taxSetIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Tax\Persistence\SpyTaxSet[]
     */
    public function findTaxSetsByIds(array $taxSetIds): iterable
    {
        $spyTaxSets = $this->getFactory()
            ->createTaxSetQuery()
            ->filterByIdTaxSet($taxSetIds, Criteria::IN)
            ->find();

        return $spyTaxSets;
    }

    /**
     * @param int[] $taxSetIds
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[]
     */
    public function findTaxSetStoragesByIds(array $taxSetIds): iterable
    {
        $spyTaxSetStorage = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->filterByFkTaxSet($taxSetIds, Criteria::IN)
            ->find();

        return $spyTaxSetStorage;
    }

    /**
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\TaxStorage\Persistence\SpyTaxSetStorage[]
     */
    public function findAllTaxSetSorage(): iterable
    {
        $spyTaxSetStorage = $this->getFactory()
            ->createTaxSetStorageQuery()
            ->find();

        return $spyTaxSetStorage;
    }
}
