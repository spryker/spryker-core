<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\MerchantRelationshipWriterStep;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\Step\CompanyBusinessUnitAssigneeKeysToIdCompanyBusinessUnitCollectionStep;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\Step\CompanyBusinessUnitOwnerKeyToIdCompanyBusinessUnitStep;
use Spryker\Zed\MerchantRelationshipDataImport\Business\Model\Step\MerchantKeyToIdMerchantStep;

/**
 * @method \Spryker\Zed\MerchantRelationshipDataImport\MerchantRelationshipDataImportConfig getConfig()
 */
class MerchantRelationshipDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createMerchantRelationshipDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getMerchantRelationshipDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createMerchantKeyToIdMerchant())
            ->addStep($this->createCompanyBusinessUnitKeyToIdCompanyBusinessUnitOwnerStep())
            ->addStep($this->createCompanyBusinessUnitAssigneeKeysToIdCompanyBusinessUnitAssigneeCollectionStep())
            ->addStep(new MerchantRelationshipWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createMerchantKeyToIdMerchant(): DataImportStepInterface
    {
        return new MerchantKeyToIdMerchantStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCompanyBusinessUnitKeyToIdCompanyBusinessUnitOwnerStep(): DataImportStepInterface
    {
        return new CompanyBusinessUnitOwnerKeyToIdCompanyBusinessUnitStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCompanyBusinessUnitAssigneeKeysToIdCompanyBusinessUnitAssigneeCollectionStep(): DataImportStepInterface
    {
        return new CompanyBusinessUnitAssigneeKeysToIdCompanyBusinessUnitCollectionStep();
    }
}
