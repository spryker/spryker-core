<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyUnitAddressDataImport\Business;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\CompanyUnitAddressWriterStep;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep;
use Spryker\Zed\CompanyUnitAddressDataImport\Business\Model\Step\CountryIsoCodeToIdCountryStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;

/**
 * @method \Spryker\Zed\CompanyUnitAddressDataImport\CompanyUnitAddressDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class CompanyUnitAddressDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer|null $dataImporterConfigurationTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyUnitAddressDataImport(
        ?DataImporterConfigurationTransfer $dataImporterConfigurationTransfer = null
    ) {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $dataImporterConfigurationTransfer ?? $this->getConfig()->getCompanyUnitAddressDataImporterConfiguration(),
        );

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
