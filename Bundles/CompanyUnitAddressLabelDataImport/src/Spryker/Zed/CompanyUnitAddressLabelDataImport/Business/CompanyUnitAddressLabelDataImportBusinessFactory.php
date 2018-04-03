<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressLabelDataImport\Business;

use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\CompanyUnitAddressLabelRelationWriterStep;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\CompanyUnitAddressLabelWriterStep;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\Step\CompanyUnitAddressKeyToIdCompanyUnitAddressStep;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\Step\CompanyUnitAddressLabelNameToIdCompanyUnitAddressLabelStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUnitAddressLabelDataImport\CompanyUnitAddressLabelDataImportConfig getConfig()
 */
class CompanyUnitAddressLabelDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyUnitAddressLabelDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyUnitAddressLabelDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CompanyUnitAddressLabelWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyUnitAddressLabelRelationDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCompanyUnitAddressLabelRelationDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCompanyUnitAddressKeyToIdCompanyUnitAddressStep())
            ->addStep($this->createCompanyUnitAddressLabelNameToIdCompanyUnitAddressLabelStep())
            ->addStep(new CompanyUnitAddressLabelRelationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\Step\CompanyUnitAddressKeyToIdCompanyUnitAddressStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCompanyUnitAddressKeyToIdCompanyUnitAddressStep()
    {
        return new CompanyUnitAddressKeyToIdCompanyUnitAddressStep();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddressLabelDataImport\Business\Model\Step\CompanyUnitAddressLabelNameToIdCompanyUnitAddressLabelStep|\Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createCompanyUnitAddressLabelNameToIdCompanyUnitAddressLabelStep()
    {
        return new CompanyUnitAddressLabelNameToIdCompanyUnitAddressLabelStep();
    }
}
