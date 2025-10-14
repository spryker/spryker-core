<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\BusinessUnitKeyToIdCompanyBusinessUnitStep;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\CompanyKeyToIdCompanyStep;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\CompanyUserWriterStep;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\CustomerReferenceToIdCustomerStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class BusinessOnBehalfDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCompanyUserDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getBusinessOnBehalfDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCompanyKeyToIdCompanyStep())
            ->addStep($this->createBusinessUnitKeyToIdCompanyBusinessUnitStep())
            ->addStep($this->createCustomerReferenceToIdCustomerStep())
            ->addStep($this->createCompanyUserWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCompanyUserWriterStep(): DataImportStepInterface
    {
        return new CompanyUserWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCompanyKeyToIdCompanyStep(): DataImportStepInterface
    {
        return new CompanyKeyToIdCompanyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createBusinessUnitKeyToIdCompanyBusinessUnitStep(): DataImportStepInterface
    {
        return new BusinessUnitKeyToIdCompanyBusinessUnitStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCustomerReferenceToIdCustomerStep(): DataImportStepInterface
    {
        return new CustomerReferenceToIdCustomerStep();
    }
}
