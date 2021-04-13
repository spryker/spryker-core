<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockAddressDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\CountryIsoCodeToIdCountryStep;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\RegionNameToIdRegionStep;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\StockAddressWriterStep;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\StockNameToIdStockStep;

/**
 * @method \Spryker\Zed\StockAddressDataImport\StockAddressDataImportConfig getConfig()
 */
class StockAddressDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getStockAddressDataImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getStockAddressDataImporterConfiguration()
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStockNameToIdStockStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createCountryIsoCodeToIdCountryStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createRegionNameToIdRegionStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStockAddressWriterStep());

        $dataImporter = $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
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
    public function createCountryIsoCodeToIdCountryStep(): DataImportStepInterface
    {
        return new CountryIsoCodeToIdCountryStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createRegionNameToIdRegionStep(): DataImportStepInterface
    {
        return new RegionNameToIdRegionStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStockAddressWriterStep(): DataImportStepInterface
    {
        return new StockAddressWriterStep();
    }
}
