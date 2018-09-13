<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\SalesOrderThresholdDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\SalesOrderThresholdDataImport\Business\Model\DataImportStep\SalesOrderThresholdWriterStep;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToCurrencyFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToSalesOrderThresholdFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\Dependency\Facade\SalesOrderThresholdDataImportToStoreFacadeInterface;
use Spryker\Zed\SalesOrderThresholdDataImport\SalesOrderThresholdDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\SalesOrderThresholdDataImport\SalesOrderThresholdDataImportConfig getConfig()
 */
class SalesOrderThresholdDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createSalesOrderThresholdDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSalesOrderThresholdDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(
            new SalesOrderThresholdWriterStep(
                $this->getSalesOrderThresholdFacade(),
                $this->getStoreFacade(),
                $this->getCurrencyFacade()
            )
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
