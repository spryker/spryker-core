<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step\ShipmentWriterStep;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step\TaxSetNameToIdTaxSetStep;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentPrice\Writer\Step\CurrencyCodeToIdCurrencyStep;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentPrice\Writer\Step\ShipmentPriceWriterStep;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentStore\Writer\Step\ShipmentMethodKeyToIdShipmentMethodStep;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentStore\Writer\Step\ShipmentMethodStoreWriterStep;
use Spryker\Zed\ShipmentDataImport\Business\ShipmentStore\Writer\Step\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\ShipmentDataImport\ShipmentDataImportConfig getConfig()
 */
class ShipmentDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getShipmentMethodStoreDataImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getShipmentStoreDataImporterConfiguration()
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createShipmentMethodKeyToIdShipmentMethodStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createShipmentMethodStoreWriterStep());
        $dataImporter = $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getShipmentDataImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShipmentDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ShipmentWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createTaxSetNameToIdTaxSetStep())
            ->addStep($this->createShipmentWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getShipmentPriceDataImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShipmentPriceDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(100);
        $dataSetStepBroker
            ->addStep($this->createShipmentMethodKeyToIdShipmentMethodStep())
            ->addStep($this->createStoreNameToIdStoreStep())
            ->addStep($this->createCurrencyCodeToIdCurrencyStep())
            ->addStep($this->createShipmentPriceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentPriceWriterStep(): DataImportStepInterface
    {
        return new ShipmentPriceWriterStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCurrencyCodeToIdCurrencyStep(): DataImportStepInterface
    {
        return new CurrencyCodeToIdCurrencyStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createTaxSetNameToIdTaxSetStep(): DataImportStepInterface
    {
        return new TaxSetNameToIdTaxSetStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentWriterStep(): DataImportStepInterface
    {
        return new ShipmentWriterStep();
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
    public function createShipmentMethodKeyToIdShipmentMethodStep(): DataImportStepInterface
    {
        return new ShipmentMethodKeyToIdShipmentMethodStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentMethodStoreWriterStep(): DataImportStepInterface
    {
        return new ShipmentMethodStoreWriterStep();
    }
}
