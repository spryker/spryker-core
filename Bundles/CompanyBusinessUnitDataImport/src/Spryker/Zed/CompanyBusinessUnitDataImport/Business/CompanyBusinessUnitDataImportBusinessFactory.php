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
 * @method \Spryker\Zed\CompanyBusinessUnitDataImport\Persistence\CompanyBusinessUnitDataImportRepositoryInterface getRepository()
 */
class CompanyBusinessUnitDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCompanyBusinessUnitDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyBusinessUnitDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCompanyKeyToIdCompanyStep())
            ->addStep($this->createParentBusinessUnitKeyToIdCompanyBusinessUnitStep())
            ->addStep($this->createCompanyBusinessUnitWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCompanyBusinessUnitUserDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyBusinessUnitUserDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createCompanyBusinessUnitUserWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getCompanyBusinessUnitAddressDataImport(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyBusinessUnitAddressDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createBusinessUnitKeyToAddressKeyStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\CompanyBusinessUnitWriterStep
     */
    public function createCompanyBusinessUnitWriterStep(): CompanyBusinessUnitWriterStep
    {
        return new CompanyBusinessUnitWriterStep();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCompanyKeyToIdCompanyStep()
    {
        return new CompanyKeyToIdCompanyStep();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\ParentBusinessUnitKeyToIdCompanyBusinessUnitStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createParentBusinessUnitKeyToIdCompanyBusinessUnitStep()
    {
        return new ParentBusinessUnitKeyToIdCompanyBusinessUnitStep(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\CompanyBusinessUnitUserWriterStep
     */
    public function createCompanyBusinessUnitUserWriterStep(): CompanyBusinessUnitUserWriterStep
    {
        return new CompanyBusinessUnitUserWriterStep(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\BusinessUnitKeyToAddressKeyStep
     */
    public function createBusinessUnitKeyToAddressKeyStep(): BusinessUnitKeyToAddressKeyStep
    {
        return new BusinessUnitKeyToAddressKeyStep(
            $this->getRepository()
        );
    }
}
