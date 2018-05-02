<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model;

use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementBaseUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnitQuery;
use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Exception\EntityNotFoundException;

class ProductMeasurementSalesUnitWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @see \Spryker\Zed\ProductMeasurementUnit\Dependency\ProductMeasurementUnitEvents::PRODUCT_CONCRETE_MEASUREMENT_UNIT_PUBLISH
     */
    protected const PRODUCT_CONCRETE_MEASUREMENT_UNIT_PUBLISH = 'ProductMeasurementUnit.product_concrete_measurement_unit.publish';

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
        $dataSet = $this->filterDataSet($dataSet);

        $spyProductMeasurementSalesUnitEntity = $this->saveProductMeasurementSalesUnit($dataSet);

        $this->addPublishEvents(
            static::PRODUCT_CONCRETE_MEASUREMENT_UNIT_PUBLISH,
            $spyProductMeasurementSalesUnitEntity->getFkProduct()
        );
    }

    /**
     * @param string $productConcreteSku
     *
     * @throws \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Exception\EntityNotFoundException
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function getProductBySku($productConcreteSku): SpyProduct
    {
        $spyProductEntity = SpyProductQuery::create()
            ->findOneBySku($productConcreteSku);

        if (!$spyProductEntity) {
            throw new EntityNotFoundException(
                sprintf('Product concrete with SKU "%s" was not found during import.', $productConcreteSku)
            );
        }

        return $spyProductEntity;
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Exception\EntityNotFoundException
     *
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\Base\SpyProductMeasurementBaseUnit
     */
    protected function getProductMeasurementBaseUnit($idProductAbstract): SpyProductMeasurementBaseUnit
    {
        $spyProductMeasurementBaseUnitEntity = SpyProductMeasurementBaseUnitQuery::create()
            ->findOneByFkProductAbstract($idProductAbstract);

        if (!$spyProductMeasurementBaseUnitEntity) {
            throw new EntityNotFoundException(
                sprintf('Product measurement base unit was not found for product abstract id "%d" during data import.', $idProductAbstract)
            );
        }

        return $spyProductMeasurementBaseUnitEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function filterDataSet(DataSetInterface $dataSet): DataSetInterface
    {
        if ($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_CONVERSION] === "") {
            $dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_CONVERSION] = null;
        }

        if ($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_PRECISION] === "") {
            $dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_PRECISION] = null;
        }

        return $dataSet;
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

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementSalesUnit
     */
    protected function saveProductMeasurementSalesUnit(DataSetInterface $dataSet): SpyProductMeasurementSalesUnit
    {
        $productMeasurementSalesUnitEntity = $this->getProductBySku($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_CONCRETE_SKU]);
        $productMeasurementBaseUnitEntity = $this->getProductMeasurementBaseUnit($productMeasurementSalesUnitEntity->getFkProductAbstract());

        $spyProductMeasurementSalesUnitEntity = SpyProductMeasurementSalesUnitQuery::create()
            ->filterByKey($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_SALES_UNIT_KEY])
            ->findOneOrCreate();

        $spyProductMeasurementSalesUnitEntity
            ->setFkProductMeasurementBaseUnit($productMeasurementBaseUnitEntity->getIdProductMeasurementBaseUnit())
            ->setFkProduct($productMeasurementSalesUnitEntity->getIdProduct())
            ->setFkProductMeasurementUnit($this->getProductMeasurementUnitIdByCode($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_CODE]))
            ->setConversion($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_CONVERSION])
            ->setPrecision($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_PRECISION])
            ->setIsDefault($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_IS_DEFAULT])
            ->setIsDisplayed($dataSet[ProductMeasurementSalesUnitDataSet::COLUMN_IS_DISPLAYED])
            ->save();

        return $spyProductMeasurementSalesUnitEntity;
    }
}
