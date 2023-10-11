<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePoint\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceType;
use Propel\Runtime\Collection\Collection;

class ShipmentTypeServicePointMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceType> $shipmentTypeServiceTypeEntities
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer $shipmentTypeServiceTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeCollectionTransfer
     */
    public function mapShipmentTypeServiceTypeEntitiesToShipmentTypeServiceTypeCollectionTransfer(
        Collection $shipmentTypeServiceTypeEntities,
        ShipmentTypeServiceTypeCollectionTransfer $shipmentTypeServiceTypeCollectionTransfer
    ): ShipmentTypeServiceTypeCollectionTransfer {
        foreach ($shipmentTypeServiceTypeEntities as $shipmentTypeServiceTypeEntity) {
            $shipmentTypeServiceTypeTransfer = $this->mapShipmentTypeServiceTypeEntityToShipmentTypeServiceTypeTransfer(
                $shipmentTypeServiceTypeEntity,
                new ShipmentTypeServiceTypeTransfer(),
            );

            $shipmentTypeServiceTypeCollectionTransfer->addShipmentTypeServiceType($shipmentTypeServiceTypeTransfer);
        }

        return $shipmentTypeServiceTypeCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ShipmentTypeServicePoint\Persistence\SpyShipmentTypeServiceType $shipmentTypeServiceTypeEntity
     * @param \Generated\Shared\Transfer\ShipmentTypeServiceTypeTransfer $shipmentTypeServiceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeServiceTypeTransfer
     */
    protected function mapShipmentTypeServiceTypeEntityToShipmentTypeServiceTypeTransfer(
        SpyShipmentTypeServiceType $shipmentTypeServiceTypeEntity,
        ShipmentTypeServiceTypeTransfer $shipmentTypeServiceTypeTransfer
    ): ShipmentTypeServiceTypeTransfer {
        return $shipmentTypeServiceTypeTransfer
            ->setShipmentType((new ShipmentTypeTransfer())->setIdShipmentType($shipmentTypeServiceTypeEntity->getFkShipmentType()))
            ->setServiceType((new ServiceTypeTransfer())->setIdServiceType($shipmentTypeServiceTypeEntity->getFkServiceType()));
    }
}
