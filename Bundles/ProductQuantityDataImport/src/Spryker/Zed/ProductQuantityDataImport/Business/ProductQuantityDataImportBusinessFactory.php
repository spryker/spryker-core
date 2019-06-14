<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ProductQuantityDataImport\Business\Model\ProductQuantityDataImportWriterStep;
use Spryker\Zed\ProductQuantityDataImport\Dependency\Service\ProductQuantityDataImportToProductQuantityServiceInterface;
use Spryker\Zed\ProductQuantityDataImport\ProductQuantityDataImportDependencyProvider;

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
        return new ProductQuantityDataImportWriterStep($this->getProductQuantityService());
    }

    /**
     * @return \Spryker\Zed\ProductQuantityDataImport\Dependency\Service\ProductQuantityDataImportToProductQuantityServiceInterface
     */
    public function getProductQuantityService(): ProductQuantityDataImportToProductQuantityServiceInterface
    {
        return $this->getProvidedDependency(ProductQuantityDataImportDependencyProvider::SERVICE_PRODUCT_QUANTITY);
    }
}
