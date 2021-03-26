<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @rerturn void
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
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
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
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
