<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockDataImport\Business\Writer\Step;

use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockDataImport\Business\Writer\DataSet\StockStoreDataSetInterface;

class StockNameToIdStockStep implements DataImportStepInterface
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
        $stockName = $dataSet[StockStoreDataSetInterface::COLUMN_WAREHOUSE_NAME];

        if (!$stockName) {
            throw new EntityNotFoundException(sprintf('Invalid warehouse name'));
        }

        $stockEntity = SpyStockQuery::create()
            ->filterByName($stockName)
            ->findOne();

        if ($stockEntity === null) {
            throw new EntityNotFoundException(sprintf('Warehouse not found'));
        }

        $dataSet[StockStoreDataSetInterface::COLUMN_ID_STOCK] = $stockEntity->getIdStock();
    }
}
