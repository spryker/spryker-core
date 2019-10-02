<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockDataImport\Business\Writer;

use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockDataImport\Business\Writer\DataSet\StockDataSetInterface;

class StockWriterStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE = '"%s" must be in the data set. Given: "%s"';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[StockDataSetInterface::COLUMN_NAME])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                static::EXCEPTION_MESSAGE,
                StockDataSetInterface::COLUMN_NAME,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        $stockEntity = SpyStockQuery::create()
            ->filterByName($dataSet[StockDataSetInterface::COLUMN_NAME])
            ->findOneOrCreate();

        $this->saveStock($stockEntity, $dataSet);
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveStock(SpyStock $stockEntity, DataSetInterface $dataSet): void
    {
        $stockEntity->setName($dataSet[StockDataSetInterface::COLUMN_NAME])
            ->setIsActive($dataSet[StockDataSetInterface::COLUMN_IS_ACTIVE])
            ->save();
    }
}
