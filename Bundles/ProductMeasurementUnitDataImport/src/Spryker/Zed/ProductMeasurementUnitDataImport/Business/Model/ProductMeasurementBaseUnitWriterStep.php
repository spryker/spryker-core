<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Exception\EntityNotFoundException;

class ProductMeasurementBaseUnitWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var int[] Keys are product measurement unit codes, values are product measurement unit ids.
     */
    protected static $productMeasurementUnitIdBuffer;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->saveProductMeasurementBaseUnit($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit
     */
    protected function saveProductMeasurementBaseUnit(DataSetInterface $dataSet): SpyProductMeasurementBaseUnit
    {
        $productMeasurementBaseUnitEntity = SpyProductMeasurementBaseUnitQuery::create()
            ->filterByFkProductAbstract($this->getIdProductAbstractBySku($dataSet[ProductMeasurementBaseUnitDataSet::COLUMN_ABSTRACT_SKU]))
            ->findOneOrCreate();

        $productMeasurementBaseUnitEntity
            ->setFkProductMeasurementUnit($this->getProductMeasurementUnitIdByCode($dataSet[ProductMeasurementBaseUnitDataSet::COLUMN_CODE]))
            ->save();

        return $productMeasurementBaseUnitEntity;
    }

    /**
     * @param string $productAbstractSku
     *
     * @throws \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductAbstractBySku($productAbstractSku): int
    {
        $spyProductAbstractEntity = SpyProductAbstractQuery::create()->findOneBySku($productAbstractSku);

        if (!$spyProductAbstractEntity) {
            throw new EntityNotFoundException(
                sprintf('Product abstract with SKU "%s" was not found during import.', $productAbstractSku)
            );
        }

        return $spyProductAbstractEntity->getIdProductAbstract();
    }

    /**
     * @param string $productMeasurementUnitCode
     *
     * @return int
     */
    protected function getProductMeasurementUnitIdByCode($productMeasurementUnitCode): int
    {
        if (!static::$productMeasurementUnitIdBuffer) {
            $this->loadProductMeasurementUnitIds();
        }

        return static::$productMeasurementUnitIdBuffer[$productMeasurementUnitCode];
    }

    /**
     * @return void
     */
    protected function loadProductMeasurementUnitIds(): void
    {
        static::$productMeasurementUnitIdBuffer = SpyProductMeasurementUnitQuery::create()
            ->find()
            ->toKeyValue('code', 'idProductMeasurementUnit');
    }
}
