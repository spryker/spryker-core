<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductQuantityDataImport\Business\Model\ProductQuantityDataImportWriterStep;

/**
 * @method \Spryker\Zed\ProductQuantityDataImport\ProductQuantityDataImportConfig getConfig()
 */
class ProductQuantityDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getProductQuantityDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductQuantityDataImportConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductQuantityDataImportWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\ProductQuantityDataImport\Business\Model\ProductQuantityDataImportWriterStep
     */
    public function createProductQuantityDataImportWriterStep(): ProductQuantityDataImportWriterStep
    {
        return new ProductQuantityDataImportWriterStep();
    }
}
