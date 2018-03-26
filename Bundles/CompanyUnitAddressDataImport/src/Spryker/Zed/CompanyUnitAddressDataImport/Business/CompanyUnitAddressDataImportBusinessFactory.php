<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business;

use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\CompanyUnitAddressWriterStep;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step\CountryIsoCodeToIdCountryStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CompanyUnitAddressDataImport\CompanyUnitAddressDataImportConfig getConfig()
 */
class CompanyUnitAddressDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyUnitAddressDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyUnitAddressDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCompanyKeyToIdCompanyStep())
            ->addStep($this->createCountryIsoCodeToIdCountryStep())
            ->addStep(new CompanyUnitAddressWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCompanyKeyToIdCompanyStep(): DataImportStepInterface
    {
        return new CompanyKeyToIdCompanyStep();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step\CountryIsoCodeToIdCountryStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCountryIsoCodeToIdCountryStep(): DataImportStepInterface
    {
        return new CountryIsoCodeToIdCountryStep();
    }
}
