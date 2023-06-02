<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeServicePointDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType\ServiceTypeKeyToServiceTypeUuidStep;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType\ShipmentTypeKeyToShipmentTypeUuidStep;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType\ShipmentTypeServiceTypeWriterStep;

/**
 * @method \Spryker\Zed\ShipmentTypeServicePointDataImport\ShipmentTypeServicePointDataImportConfig getConfig()
 */
class ShipmentTypeServicePointDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getShipmentTypeServiceTypeDataImporter(): DataImporterInterface
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getShipmentTypeServiceTypeDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createShipmentTypeKeyToShipmentTypeUuidStep())
            ->addStep($this->createServiceTypeKeyToServiceTypeUuidStep())
            ->addStep($this->createShipmentTypeServiceTypeWriterStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentTypeKeyToShipmentTypeUuidStep(): DataImportStepInterface
    {
        return new ShipmentTypeKeyToShipmentTypeUuidStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createServiceTypeKeyToServiceTypeUuidStep(): DataImportStepInterface
    {
        return new ServiceTypeKeyToServiceTypeUuidStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentTypeServiceTypeWriterStep(): DataImportStepInterface
    {
        return new ShipmentTypeServiceTypeWriterStep();
    }
}
