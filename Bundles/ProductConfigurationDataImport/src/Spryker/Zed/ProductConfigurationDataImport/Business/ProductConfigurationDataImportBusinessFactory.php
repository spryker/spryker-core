<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductConfigurationDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductConfigurationDataImport\Business\Model\ProductConfigurationWriterStep;
use Spryker\Zed\ProductConfigurationDataImport\Business\Model\Step\ProductConcreteSkuToIdProductConcreteStep;

/**
 * @method \Spryker\Zed\ProductConfigurationDataImport\ProductConfigurationDataImportConfig getConfig()
 */
class ProductConfigurationDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createProductConfigurationDataImport()
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
