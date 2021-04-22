<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress;

use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\DataSet\StockAddressDataSetInterface;

class StockNameToIdStockStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $stockName = $dataSet[StockAddressDataSetInterface::COLUMN_WAREHOUSE_NAME];

        if (!$stockName) {
            throw new InvalidDataException(sprintf('Warehouse name is missing.'));
        }

        $idStock = SpyStockQuery::create()
            ->filterByName($stockName)
            ->select(SpyStockTableMap::COL_ID_STOCK)
            ->findOne();

        if (!$idStock) {
            throw new EntityNotFoundException(sprintf('Warehouse "%s" not found.', $stockName));
        }

        $dataSet[StockAddressDataSetInterface::ID_STOCK] = $idStock;
    }
}
