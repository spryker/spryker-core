<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\Step;

use Orm\Zed\Shipment\Persistence\SpyShipmentMethodQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentDataImport\Business\Shipment\Writer\DataSet\ShipmentDataSetInterface;

class ShipmentMethodWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var int
     */
    public const BULK_SIZE = 100;

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
        $shipmentMethodEntity = SpyShipmentMethodQuery::create()
            ->filterByShipmentMethodKey($dataSet[ShipmentDataSetInterface::COL_SHIPMENT_METHOD_KEY])
            ->findOneOrCreate();

        $shipmentMethodEntity->fromArray($dataSet->getArrayCopy());
        $shipmentMethodEntity
            ->setFkShipmentCarrier($dataSet[ShipmentDataSetInterface::COL_ID_CARRIER])
            ->setName($dataSet[ShipmentDataSetInterface::COL_NAME])
            ->setFkTaxSet($dataSet[ShipmentDataSetInterface::COL_ID_TAX_SET]);

        if ($shipmentMethodEntity->isNew() || $shipmentMethodEntity->isModified()) {
            $shipmentMethodEntity->save();

            $this->addPublishEvents(static::SHIPMENT_METHOD_PUBLISH, $shipmentMethodEntity->getIdShipmentMethod());
        }
    }
}
