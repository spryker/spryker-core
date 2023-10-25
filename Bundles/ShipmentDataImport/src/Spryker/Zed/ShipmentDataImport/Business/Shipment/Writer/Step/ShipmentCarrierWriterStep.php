<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentCarrierQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet\ShipmentDataSetInterface;

class ShipmentCarrierWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig::SHIPMENT_CARRIER_PUBLISH
     *
     * @var string
     */
    protected const SHIPMENT_CARRIER_PUBLISH = 'Shipment.shipment_carrier.publish';

    /**
     * @var array<int>
     */
    protected static $idShipmentCarrierCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $carrierName = $dataSet[ShipmentDataSetInterface::COL_CARRIER_NAME];

        if (!$carrierName) {
            throw new DataKeyNotFoundInDataSetException('Carrier name is missing');
        }

        if (!isset(static::$idShipmentCarrierCache[$carrierName])) {
            $shipmentCarrierEntity = SpyShipmentCarrierQuery::create()
                ->filterByName($carrierName)
                ->findOneOrCreate();

            if ($shipmentCarrierEntity->isNew()) {
                $shipmentCarrierEntity->save();

                $this->addPublishEvents(static::SHIPMENT_CARRIER_PUBLISH, $shipmentCarrierEntity->getIdShipmentCarrier());
            }

            static::$idShipmentCarrierCache[$carrierName] = $shipmentCarrierEntity->getIdShipmentCarrier();
        }

        $dataSet[ShipmentDataSetInterface::COL_ID_CARRIER] = static::$idShipmentCarrierCache[$carrierName];
    }
}
