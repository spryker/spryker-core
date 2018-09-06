<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\ShoppingListDataImport\Business\Model\ShoppingListItemWriterStep;
use Spryker\Zed\ShoppingListDataImport\Business\Model\ShoppingListPermissionWriterStep;
use Spryker\Zed\ShoppingListDataImport\Business\Model\ShoppingListWriterStep;

/**
 * @method \Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig getConfig()
 */
class ShoppingListDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getShoppingListDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShoppingListDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createShoppingListWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getShoppingListItemDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShoppingListItemDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createShoppingListItemWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getShoppingListPermissionDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShoppingListPermissionDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createShoppingListPermissionWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\ShoppingListDataImport\Business\Model\ShoppingListWriterStep
     */
    protected function createShoppingListWriterStep(): ShoppingListWriterStep
    {
        return new ShoppingListWriterStep();
    }

    /**
     * @return \Spryker\Zed\ShoppingListDataImport\Business\Model\ShoppingListItemWriterStep
     */
    protected function createShoppingListItemWriterStep(): ShoppingListItemWriterStep
    {
        return new ShoppingListItemWriterStep();
    }

    /**
     * @return \Spryker\Zed\ShoppingListDataImport\Business\Model\ShoppingListPermissionWriterStep
     */
    protected function createShoppingListPermissionWriterStep(): ShoppingListPermissionWriterStep
    {
        return new ShoppingListPermissionWriterStep();
    }
}
