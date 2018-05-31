<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model;

use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Exception\EntityNotFoundException;

class ProductMeasurementSalesUnitStoreWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var int[] Keys are store names, values are store ids.
     */
    protected static $idStoreBuffer;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->saveProductMeasurementSalesUnitStore($dataSet);
    }

    /**
     * @param string $productMeasurementSalesUnitKey
     *
     * @throws \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductMeasurementSalesUnitByKey($productMeasurementSalesUnitKey): int
    {
        $spyProductMeasurementSalesUnitEntity = SpyProductMeasurementSalesUnitQuery::create()
            ->findOneByKey($productMeasurementSalesUnitKey);

        if (!$spyProductMeasurementSalesUnitEntity) {
            throw new EntityNotFoundException(
                sprintf('Product measurement sales unit with key "%s" was not found during import.', $productMeasurementSalesUnitKey)
            );
        }

        return $spyProductMeasurementSalesUnitEntity->getIdProductMeasurementSalesUnit();
    }

    /**
     * @param string $storeName
     *
     * @return int
     */
    protected function getIdStoreByName($storeName): int
    {
        if (!static::$idStoreBuffer) {
            $this->loadStoreIds();
        }

        return static::$idStoreBuffer[$storeName];
    }

    /**
     * @return void
     */
    protected function loadStoreIds(): void
    {
        static::$idStoreBuffer = SpyStoreQuery::create()
            ->find()
            ->toKeyValue('name', 'idStore');
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function saveProductMeasurementSalesUnitStore(DataSetInterface $dataSet): void
    {
        SpyProductMeasurementSalesUnitStoreQuery::create()
            ->filterByFkProductMeasurementSalesUnit($this->getIdProductMeasurementSalesUnitByKey($dataSet[ProductMeasurementSalesUnitStoreDataSet::COLUMN_SALES_UNIT_KEY]))
            ->filterByFkStore($this->getIdStoreByName($dataSet[ProductMeasurementSalesUnitStoreDataSet::COLUMN_STORE_NAME]))
            ->findOneOrCreate()
            ->save();
    }
}
