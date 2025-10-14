<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\SalesOrderThresholdDataImport\Business\Model\DataImportStep\SalesOrderThresholdWriterStep;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToCurrencyFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToStoreFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\SalesOrderThresholdDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\SalesOrderThresholdDataImport\SalesOrderThresholdDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class SalesOrderThresholdDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createSalesOrderThresholdDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getSalesOrderThresholdDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(
            new SalesOrderThresholdWriterStep(
                $this->getSalesOrderThresholdFacade(),
                $this->getStoreFacade(),
                $this->getCurrencyFacade(),
            ),
        );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface
     */
    public function getSalesOrderThresholdFacade(): SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDataImportDependencyProvider::FACADE_SALES_ORDER_THRESHOLD);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToStoreFacadeInterface
     */
    public function getStoreFacade(): SalesOrderThresholdDataImportToStoreFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDataImportDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): SalesOrderThresholdDataImportToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(SalesOrderThresholdDataImportDependencyProvider::FACADE_CURRENCY);
    }
}
