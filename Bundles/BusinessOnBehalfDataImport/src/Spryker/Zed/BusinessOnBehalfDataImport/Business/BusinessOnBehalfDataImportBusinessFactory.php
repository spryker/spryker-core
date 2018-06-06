<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalfDataImport\Business;

use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\BusinessUnitKeyToIdCompanyBusinessUnitStep;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\CompanyKeyToIdCompanyStep;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\CompanyUserWriterStep;
use Spryker\Zed\BusinessOnBehalfDataImport\Business\Model\Step\CompanyUser\CustomerReferenceToIdCustomerStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportConfig getConfig()
 */
class BusinessOnBehalfDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyUserDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getBusinessOnBehalfDataImporterConfiguration());

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
    protected function createCompanyUserWriterStep(): DataImportStepInterface
    {
        return new CompanyUserWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCompanyKeyToIdCompanyStep(): DataImportStepInterface
    {
        return new CompanyKeyToIdCompanyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createBusinessUnitKeyToIdCompanyBusinessUnitStep(): DataImportStepInterface
    {
        return new BusinessUnitKeyToIdCompanyBusinessUnitStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCustomerReferenceToIdCustomerStep(): DataImportStepInterface
    {
        return new CustomerReferenceToIdCustomerStep();
    }
}
