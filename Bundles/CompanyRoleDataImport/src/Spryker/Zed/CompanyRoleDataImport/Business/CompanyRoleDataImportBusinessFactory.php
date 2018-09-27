<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CompanyRoleDataImport\Business;

use Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole\CompanyRolePermissionWriterStep;
use Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole\CompanyRoleWriterStep;
use Spryker\Zed\CompanyRoleDataImport\Business\Model\CompanyRole\CompanyUserRoleWriterStep;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyRoleDataImport\CompanyRoleDataImportConfig getConfig()
 */
class CompanyRoleDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createCompanyRoleDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCompanyRoleDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CompanyRoleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createCompanyRolePermissionDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCompanyRolePermissionDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CompanyRolePermissionWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createCompanyUserRoleDataImport()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getCompanyUserRoleDataImporterConfiguration()
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CompanyUserRoleWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }
}
