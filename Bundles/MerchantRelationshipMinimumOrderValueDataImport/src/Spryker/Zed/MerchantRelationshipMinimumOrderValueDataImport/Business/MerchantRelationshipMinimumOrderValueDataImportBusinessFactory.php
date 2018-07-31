<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Business\Model\DataImportStep\MerchantRelationshipMinimumOrderValueWriterStep;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToCurrencyFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToStoreFacadeInterface;
use Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\MerchantRelationshipMinimumOrderValueDataImportDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\MerchantRelationshipMinimumOrderValueDataImportConfig getConfig()
 */
class MerchantRelationshipMinimumOrderValueDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createMerchantRelationshipMinimumOrderValueDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantRelationshipMinimumOrderValueDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(
            new MerchantRelationshipMinimumOrderValueWriterStep(
                $this->getMerchantRelationshipMinimumOrderValueFacade(),
                $this->getMerchantRelationshipFacade(),
                $this->getStoreFacade(),
                $this->getCurrencyFacade()
            )
        );

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface
     */
    public function getMerchantRelationshipMinimumOrderValueFacade(): MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipMinimumOrderValueFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDataImportDependencyProvider::FACADE_MERCHANT_RELATIONSHIP_MINIMUM_ORDER_VALUE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface
     */
    public function getMerchantRelationshipFacade(): MerchantRelationshipMinimumOrderValueDataImportToMerchantRelationshipFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDataImportDependencyProvider::FACADE_MERCHANT_RELATIONSHIP);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToStoreFacadeInterface
     */
    public function getStoreFacade(): MerchantRelationshipMinimumOrderValueDataImportToStoreFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDataImportDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipMinimumOrderValueDataImport\Dependency\Facade\MerchantRelationshipMinimumOrderValueDataImportToCurrencyFacadeInterface
     */
    public function getCurrencyFacade(): MerchantRelationshipMinimumOrderValueDataImportToCurrencyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantRelationshipMinimumOrderValueDataImportDependencyProvider::FACADE_CURRENCY);
    }
}
