<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE = 'Could not find store by name "%s"';

    /**
     * @var int[]
     */
    protected $idStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[PriceProductScheduleDataSetInterface::KEY_STORE];
        if (!isset($this->idStoreCache[$storeName])) {
            $idStore = $this->createStoreQuery()
                ->select(SpyStoreTableMap::COL_ID_STORE)
                ->findOneByName($storeName);

            if ($idStore === null) {
                throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE, $storeName));
            }

            $this->idStoreCache[$storeName] = $idStore;
        }

        $dataSet[PriceProductScheduleDataSetInterface::FK_STORE] = $this->idStoreCache[$storeName];
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    protected function createStoreQuery(): SpyStoreQuery
    {
        return SpyStoreQuery::create();
    }
}
