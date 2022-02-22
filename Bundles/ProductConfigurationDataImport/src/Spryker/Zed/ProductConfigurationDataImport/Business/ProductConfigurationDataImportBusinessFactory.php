<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\ProductConfigurationDataImport\Business\Model\ProductConfigurationWriterStep;
use Spryker\Zed\ProductConfigurationDataImport\Business\Model\Step\ProductConcreteSkuToIdProductConcreteStep;

/**
 * @method \Spryker\Zed\ProductConfigurationDataImport\ProductConfigurationDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ProductConfigurationDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductConfigurationDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductConfigurationDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();

        $dataSetStepBroker->addStep($this->createConcreteSkuToIdProductStep());
        $dataSetStepBroker->addStep(new ProductConfigurationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\ProductConfigurationDataImport\Business\Model\Step\ProductConcreteSkuToIdProductConcreteStep
     */
    public function createConcreteSkuToIdProductStep(): ProductConcreteSkuToIdProductConcreteStep
    {
        return new ProductConcreteSkuToIdProductConcreteStep();
    }
}
