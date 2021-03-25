<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress;

use Orm\Zed\StockAddress\Persistence\SpyStockAddressQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\DataSet\StockAddressDataSetInterface;

class StockAddressWriterStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        StockAddressDataSetInterface::COLUMN_ADDRESS1,
        StockAddressDataSetInterface::COLUMN_CITY,
        StockAddressDataSetInterface::COLUMN_ZIP_CODE,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->validateDataSet($dataSet);

        $stockAddressEntity = SpyStockAddressQuery::create()
            ->filterByFkStock($dataSet[StockAddressDataSetInterface::ID_STOCK])
            ->findOneOrCreate();

        $stockAddressEntity->fromArray($dataSet->getArrayCopy());
        $stockAddressEntity->setFkCountry($dataSet[StockAddressDataSetInterface::ID_COUNTRY])
            ->setFkRegion($dataSet[StockAddressDataSetInterface::ID_REGION]);

        $stockAddressEntity->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @rerturn void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            $this->validateDataSetKey($dataSet, $requiredDataSetKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $requiredDataSetKey
     *
     * @return void
     */
    protected function validateDataSetKey(DataSetInterface $dataSet, string $requiredDataSetKey): void
    {
        if (!$dataSet[$requiredDataSetKey]) {
            throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
        }
    }
}
