<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockDataImport\Business\Writer\Step;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockDataImport\Business\Writer\DataSet\StockStoreDataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE_ENTITY_NOT_FOUND = 'Store not found';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[StockStoreDataSetInterface::COLUMN_STORE_NAME];

        if (!$storeName) {
            throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE_ENTITY_NOT_FOUND));
        }

        $storeEntity = SpyStoreQuery::create()
            ->filterByName($storeName)
            ->findOne();

        if ($storeEntity === null) {
            throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE_ENTITY_NOT_FOUND));
        }

        $dataSet[StockStoreDataSetInterface::COLUMN_ID_STORE] = $storeEntity->getIdStore();
    }
}
