<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataImportStep\ShipmentTypeServiceType;

use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Business\DataSet\ShipmentTypeServiceTypeDataSetInterface;

class ShipmentTypeServiceTypeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $shipmentTypeServiceTypeEntity = $this->getShipmentTypeServiceTypeQuery()
            ->filterByShipmentTypeUuid($dataSet[ShipmentTypeServiceTypeDataSetInterface::SHIPMENT_TYPE_UUID])
            ->findOneOrCreate();

        $shipmentTypeServiceTypeEntity->setServiceTypeUuid($dataSet[ShipmentTypeServiceTypeDataSetInterface::SERVICE_TYPE_UUID]);
        if ($shipmentTypeServiceTypeEntity->isNew() || $shipmentTypeServiceTypeEntity->isModified()) {
            $shipmentTypeServiceTypeEntity->save();
        }
    }

    /**
     * @return \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceTypeQuery
     */
    protected function getShipmentTypeServiceTypeQuery(): SpyShipmentTypeServiceTypeQuery
    {
        return SpyShipmentTypeServiceTypeQuery::create();
    }
}
