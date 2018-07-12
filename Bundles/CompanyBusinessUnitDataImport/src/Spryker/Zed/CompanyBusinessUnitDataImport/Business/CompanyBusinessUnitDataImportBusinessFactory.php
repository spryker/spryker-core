<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitDataImport\Business;

use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\CompanyBusinessUnitWriterStep;
use Spryker\Zed\CompanyBusinessUnitDataImport\Business\Model\Step\CompanyKeyToIdCompanyStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig getConfig()
 */
class CompanyBusinessUnitDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCompanyBusinessUnitDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCompanyBusinessUnitDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCompanyKeyToIdCompanyStep())
            ->addStep(new CompanyBusinessUnitWriterStep());

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
}
