<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model;

use Orm\Zed\ProductMeasurementUnit\Persistence\SpyProductMeasurementUnitQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductMeasurementUnit\Dependency\ProductMeasurementUnitEvents;

class ProductMeasurementUnitWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $productMeasurementUnitEntity = SpyProductMeasurementUnitQuery::create()
            ->filterByCode($dataSet[ProductMeasurementUnitDataSet::KEY_CODE])
            ->findOneOrCreate();

        $productMeasurementUnitEntity
            ->setName($dataSet[ProductMeasurementUnitDataSet::KEY_NAME])
            ->setDefaultPrecision($this->filterDefaultPrecision($dataSet[ProductMeasurementUnitDataSet::KEY_DEFAULT_PRECISION]))
            ->save();

        $this->addPublishEvents(
            ProductMeasurementUnitEvents::PRODUCT_MEASUREMENT_UNIT_PUBLISH,
            $productMeasurementUnitEntity->getIdProductMeasurementUnit()
        );
    }

    /**
     * @param int|float|string $defaultPrecision
     *
     * @return int
     */
    protected function filterDefaultPrecision($defaultPrecision)
    {
        if ($defaultPrecision === "") {
            return ProductMeasurementUnitDataSet::DEFAULT_PRECISION;
        }

        return (int)$defaultPrecision;
    }
}
