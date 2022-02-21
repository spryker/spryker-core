<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductQuantityDataImport\Business\Model\ProductQuantityDataImportWriterStep;

/**
 * @method \Spryker\Zed\ProductQuantityDataImport\ProductQuantityDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker()
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ProductQuantityDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
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
