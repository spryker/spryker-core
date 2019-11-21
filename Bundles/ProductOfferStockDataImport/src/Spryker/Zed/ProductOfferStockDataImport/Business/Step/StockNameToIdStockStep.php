<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;

class StockNameToIdStockStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $stockName = $dataSet[ProductOfferStockDataSetInterface::PRODUCT_STOCK_NAME];

        if (!$stockName) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                '"%s" key must be in the data set. Given: "%s"',
                ProductOfferStockDataSetInterface::PRODUCT_STOCK_NAME,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        $stockEntity = SpyStockQuery::create()
            ->filterByName($stockName)
            ->findOne();

        if (!$stockEntity) {
            throw new EntityNotFoundException(sprintf('Stock not found for name %s', $stockName));
        }

        $dataSet[ProductOfferStockDataSetInterface::FK_STOCK] = $stockEntity->getIdStock();
    }
}
