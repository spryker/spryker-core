<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\Common\ShipmentTypeKeyToIdShipmentTypeDataImportStep;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentMethodShipmentType\ShipmentMethodKeyToIdShipmentMethodDataImportStep;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentMethodShipmentType\ShipmentMethodWriteDataImportStep;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentType\ShipmentTypeWriteDataImportStep;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentTypeStore\ShipmentTypeStoreWriteDataImportStep;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentTypeStore\StoreNameToIdStoreDataImportStep;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataSet\ShipmentMethodShipmentTypeDataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataSet\ShipmentTypeStoreDataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\Validator\DataSetValidator;
use Spryker\Zed\ShipmentTypeDataImport\Business\Validator\DataSetValidatorInterface;

/**
 * @method \Spryker\Zed\ShipmentTypeDataImport\ShipmentTypeDataImportConfig getConfig()
 */
class ShipmentTypeDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShipmentTypeDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getShipmentTypeDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createShipmentTypeWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShipmentTypeStoreDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getShipmentTypeStoreDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $shipmentTypeKeyToIdShipmentTypeDataImportStep = $this->createShipmentTypeKeyToIdShipmentTypeDataImportStep(
            ShipmentTypeStoreDataSetInterface::COLUMN_SHIPMENT_TYPE_KEY,
            ShipmentTypeStoreDataSetInterface::ID_SHIPMENT_TYPE,
        );
        $dataSetStepBroker->addStep($shipmentTypeKeyToIdShipmentTypeDataImportStep);
        $dataSetStepBroker->addStep($this->createStoreNameToIdStoreDataImportStep());
        $dataSetStepBroker->addStep($this->createShipmentTypeStoreWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShipmentMethodShipmentTypeDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getShipmentMethodShipmentTypeDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $shipmentTypeKeyToIdShipmentTypeDataImportStep = $this->createShipmentTypeKeyToIdShipmentTypeDataImportStep(
            ShipmentMethodShipmentTypeDataSetInterface::COLUMN_SHIPMENT_TYPE_KEY,
            ShipmentMethodShipmentTypeDataSetInterface::ID_SHIPMENT_TYPE,
        );
        $dataSetStepBroker->addStep($this->createShipmentMethodKeyToIdShipmentMethodDataImportStep());
        $dataSetStepBroker->addStep($shipmentTypeKeyToIdShipmentTypeDataImportStep);
        $dataSetStepBroker->addStep($this->createShipmentMethodWriteDataImportStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentTypeWriteDataImportStep(): DataImportStepInterface
    {
        return new ShipmentTypeWriteDataImportStep(
            $this->createDataSetValidator(),
        );
    }

    /**
     * @param string $dataSetColumnShipmentTypeKey
     * @param string $dataSetColumnIdShipmentType
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentTypeKeyToIdShipmentTypeDataImportStep(
        string $dataSetColumnShipmentTypeKey,
        string $dataSetColumnIdShipmentType
    ): DataImportStepInterface {
        return new ShipmentTypeKeyToIdShipmentTypeDataImportStep(
            $dataSetColumnShipmentTypeKey,
            $dataSetColumnIdShipmentType,
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreNameToIdStoreDataImportStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentTypeStoreWriteDataImportStep(): DataImportStepInterface
    {
        return new ShipmentTypeStoreWriteDataImportStep(
            $this->createDataSetValidator(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentMethodKeyToIdShipmentMethodDataImportStep(): DataImportStepInterface
    {
        return new ShipmentMethodKeyToIdShipmentMethodDataImportStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentMethodWriteDataImportStep(): DataImportStepInterface
    {
        return new ShipmentMethodWriteDataImportStep();
    }

    /**
     * @return \Spryker\Zed\ShipmentTypeDataImport\Business\Validator\DataSetValidatorInterface
     */
    public function createDataSetValidator(): DataSetValidatorInterface
    {
        return new DataSetValidator();
    }
}
