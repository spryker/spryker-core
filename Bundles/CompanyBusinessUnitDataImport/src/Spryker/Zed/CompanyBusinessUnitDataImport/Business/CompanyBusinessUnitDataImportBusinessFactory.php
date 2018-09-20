<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business;

use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\CompanyBusinessUnitWriterStep;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\BusinessUnitKeyToAddressKeyStep;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\CompanyBusinessUnitUserWriterStep;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\ParentBusinessUnitKeyToIdCompanyBusinessUnitStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig getConfig()
 */
class CompanyBusinessUnitDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyBusinessUnitDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyBusinessUnitDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCompanyKeyToIdCompanyStep())
            ->addStep($this->createParentBusinessUnitKeyToIdCompanyBusinessUnitStep())
            ->addStep(new CompanyBusinessUnitWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyBusinessUnitUserDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyBusinessUnitUserDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CompanyBusinessUnitUserWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyBusinessUnitAddressDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyBusinessUnitAddressDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new BusinessUnitKeyToAddressKeyStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCompanyKeyToIdCompanyStep()
    {
        return new CompanyKeyToIdCompanyStep();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\ParentBusinessUnitKeyToIdCompanyBusinessUnitStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createParentBusinessUnitKeyToIdCompanyBusinessUnitStep()
    {
        return new ParentBusinessUnitKeyToIdCompanyBusinessUnitStep();
    }
}
