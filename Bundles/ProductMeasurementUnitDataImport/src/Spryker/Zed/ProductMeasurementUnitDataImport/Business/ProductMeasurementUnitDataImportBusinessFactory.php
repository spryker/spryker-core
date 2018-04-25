<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementUnitWriterStep;

/**
 * @method \Spryker\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportConfig getConfig()
 */
class ProductMeasurementUnitDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductMeasurementUnitDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductMeasurementUnitDataImportConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductMeasurementUnitWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\ProductMeasurementUnitDataImport\Business\Model\ProductMeasurementUnitWriterStep
     */
    public function createProductMeasurementUnitWriterStep(): ProductMeasurementUnitWriterStep
    {
        return new ProductMeasurementUnitWriterStep();
    }
}
