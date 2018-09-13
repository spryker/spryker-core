<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Business\Model\DataImportStep\MerchantRelationshipSalesOrderThresholdWriterStep;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\MerchantRelationshipSalesOrderThresholdDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\MerchantRelationshipSalesOrderThresholdDataImportConfig getConfig()
 */
class MerchantRelationshipSalesOrderThresholdDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createMerchantRelationshipSalesOrderThresholdDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantRelationshipSalesOrderThresholdDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(
            new MerchantRelationshipSalesOrderThresholdWriterStep(
                $this->getMerchantRelationshipSalesOrderThresholdFacade(),
                $this->getMerchantRelationshipFacade(),
                $this->getStoreFacade(),
                $this->getCurrencyFacade()
            )
        );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeInterface
     */
    public function getMerchantRelationshipSalesOrderThresholdFacade(): MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipSalesOrderThresholdFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdDataImportDependencyProvider::FACADE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipSalesOrderThresholdDataImportToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdDataImportDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantRelationshipSalesOrderThresholdDataImportToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdDataImportDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Dependency\Facade\MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): MerchantRelationshipSalesOrderThresholdDataImportToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipSalesOrderThresholdDataImportDependencyProvider::FACADE_CURRENCY);
    }
}
