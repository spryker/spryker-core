<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\StockDataImport\Business\Writer\Step\NameValidatorStep;
use Spryker\Zed\StockDataImport\Business\Writer\Step\StockNameToIdStockStep;
use Spryker\Zed\StockDataImport\Business\Writer\Step\StoreNameToIdStoreStep;
use Spryker\Zed\StockDataImport\Business\Writer\StockStoreWriterStep;
use Spryker\Zed\StockDataImport\Business\Writer\StockWriterStep;

/**
 * @method \Spryker\Zed\StockDataImport\StockDataImportConfig getConfig()
 * @method \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware createTransactionAwareDataSetStepBroker($bulkSize = null)
 * @method \Spryker\Zed\DataImport\Business\Model\DataImporter getCsvDataImporterFromConfig(\Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
 */
class StockDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getStockDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getStockDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createNameValidatorStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStockWriterStep());

        $dataImporter = $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getStockStoreDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getStockStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStockNameToIdStockStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStockStoreWriterStep());

        $dataImporter = $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createNameValidatorStep(): DataImportStepInterface
    {
        return new NameValidatorStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStockWriterStep(): DataImportStepInterface
    {
        return new StockWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStockNameToIdStockStep(): DataImportStepInterface
    {
        return new StockNameToIdStockStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStockStoreWriterStep(): DataImportStepInterface
    {
        return new StockStoreWriterStep();
    }
}
