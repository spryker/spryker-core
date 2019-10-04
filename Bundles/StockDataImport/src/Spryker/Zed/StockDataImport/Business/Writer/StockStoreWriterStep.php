<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockDataImport\Business\Writer;

use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Orm\Zed\Stock\Persistence\SpyStockStore;
use Orm\Zed\Stock\Persistence\SpyStockStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockDataImport\Business\Writer\DataSet\StockStoreDataSetInterface;

class StockStoreWriterStep implements DataImportStepInterface
{
    protected const EXCEPTION_MESSAGE = '"%s" and %s must be in the data set. Given: "%s"';
    protected const NOT_FOUND_EXCEPTION_MESSAGE = 'Stock Entity not found';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $stockEntity = SpyStockQuery::create()
            ->filterByIdStock($dataSet[StockStoreDataSetInterface::COLUMN_ID_STOCK])
            ->findOne();

        if ($stockEntity === null) {
            throw new EntityNotFoundException(static::NOT_FOUND_EXCEPTION_MESSAGE);
        }

        $stockStoreEntity = SpyStockStoreQuery::create()
            ->filterByFkStore($dataSet[StockStoreDataSetInterface::COLUMN_ID_STORE])
            ->filterByFkStock($dataSet[StockStoreDataSetInterface::COLUMN_ID_STOCK])
            ->findOneOrCreate();

        if (!$stockStoreEntity->isNew()) {
            return;
        }

        $this->createStockStore($stockStoreEntity, $dataSet);
    }

    /**
     * @param \Orm\Zed\Stock\Persistence\SpyStockStore $stockStoreEntity
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function createStockStore(SpyStockStore $stockStoreEntity, DataSetInterface $dataSet): void
    {
        $stockStoreEntity->setFkStore($dataSet[StockStoreDataSetInterface::COLUMN_ID_STORE])
            ->setFkStock($dataSet[StockStoreDataSetInterface::COLUMN_ID_STOCK])
            ->save();
    }
}
