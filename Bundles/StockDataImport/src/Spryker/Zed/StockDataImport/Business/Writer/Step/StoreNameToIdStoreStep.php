<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockDataImport\Business\Writer\Step;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockDataImport\Business\Writer\DataSet\StockStoreDataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
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
            throw new EntityNotFoundException(sprintf('Invalid store name'));
        }

        $storeEntity = SpyStoreQuery::create()
            ->filterByName($storeName)
            ->findOne();

        if ($storeEntity === null) {
            throw new EntityNotFoundException(sprintf('Store not found'));
        }

        $dataSet[StockStoreDataSetInterface::COLUMN_ID_STORE] = $storeEntity->getIdStore();
    }
}
