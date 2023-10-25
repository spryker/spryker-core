<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeDataImport\Business\DataImportStep\ShipmentMethodShipmentType;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeDataImport\Business\DataSet\ShipmentMethodShipmentTypeDataSetInterface;

class ShipmentMethodWriteDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\ShipmentTypeStorage\ShipmentTypeStorageConfig::SHIPMENT_METHOD_PUBLISH
     *
     * @var string
     */
    protected const SHIPMENT_METHOD_PUBLISH = 'Shipment.shipment_method.publish';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var \Orm\Zed\Shipment\Persistence\SpyShipmentMethod $shipmentMethodEntity */
        $shipmentMethodEntity = $this->getShipmentMethodQuery()
            ->findOneByIdShipmentMethod($dataSet[ShipmentMethodShipmentTypeDataSetInterface::ID_SHIPMENT_METHOD]);

        $shipmentMethodEntity->setFkShipmentType(
            $dataSet[ShipmentMethodShipmentTypeDataSetInterface::ID_SHIPMENT_TYPE],
        );

        if ($shipmentMethodEntity->isModified()) {
            $shipmentMethodEntity->save();

            $this->addPublishEvents(static::SHIPMENT_METHOD_PUBLISH, $shipmentMethodEntity->getIdShipmentMethod());
        }
    }

    /**
     * @return \Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery
     */
    protected function getShipmentMethodQuery(): SpyShipmentMethodQuery
    {
        return SpyShipmentMethodQuery::create();
    }
}
