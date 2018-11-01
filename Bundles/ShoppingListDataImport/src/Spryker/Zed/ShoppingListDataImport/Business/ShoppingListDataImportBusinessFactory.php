<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\BusinessUnitKeyToIdCompanyBusinessUnitStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\CompanyUserKeyToIdCompanyUserStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\CustomerReferenceValidationStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\ProductConcreteSkuValidationStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\ShoppingListCompanyBusinessUnitWriterStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\ShoppingListCompanyUserWriterStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\ShoppingListItemWriterStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\ShoppingListKeyToIdShoppingList;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\ShoppingListPermissionGroupNameToIdShoppingListPermissionGroupStep;
use Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep\ShoppingListWriterStep;

/**
 * @method \Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker()
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class ShoppingListDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShoppingListDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShoppingListDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createCustomerReferenceValidationStep())
            ->addStep($this->createShoppingListWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShoppingListItemDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShoppingListItemDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createShoppingListKeyToIdShoppingList())
            ->addStep($this->createProductConcreteSkuValidationStep())
            ->addStep($this->createShoppingListItemWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShoppingListCompanyUserDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShoppingListCompanyUserDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createShoppingListKeyToIdShoppingList())
            ->addStep($this->createCompanyUserKeyToIdCompanyUserStep())
            ->addStep($this->createShoppingListPermissionGroupNameToIdShoppingListPermissionGroupStep())
            ->addStep($this->createShoppingListCompanyUserWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShoppingListCompanyBusinessUnitDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShoppingListCompanyBusinessUnitDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createShoppingListKeyToIdShoppingList())
            ->addStep($this->createBusinessUnitKeyToIdCompanyBusinessUnitStep())
            ->addStep($this->createShoppingListPermissionGroupNameToIdShoppingListPermissionGroupStep())
            ->addStep($this->createShoppingListCompanyBusinessUnitWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShoppingListWriterStep(): DataImportStepInterface
    {
        return new ShoppingListWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShoppingListItemWriterStep(): DataImportStepInterface
    {
        return new ShoppingListItemWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShoppingListCompanyUserWriterStep(): DataImportStepInterface
    {
        return new ShoppingListCompanyUserWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShoppingListCompanyBusinessUnitWriterStep(): DataImportStepInterface
    {
        return new ShoppingListCompanyBusinessUnitWriterStep();
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
    public function createCompanyUserKeyToIdCompanyUserStep(): DataImportStepInterface
    {
        return new CompanyUserKeyToIdCompanyUserStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShoppingListKeyToIdShoppingList(): DataImportStepInterface
    {
        return new ShoppingListKeyToIdShoppingList();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShoppingListPermissionGroupNameToIdShoppingListPermissionGroupStep(): DataImportStepInterface
    {
        return new ShoppingListPermissionGroupNameToIdShoppingListPermissionGroupStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCustomerReferenceValidationStep(): DataImportStepInterface
    {
        return new CustomerReferenceValidationStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductConcreteSkuValidationStep(): DataImportStepInterface
    {
        return new ProductConcreteSkuValidationStep();
    }
}
