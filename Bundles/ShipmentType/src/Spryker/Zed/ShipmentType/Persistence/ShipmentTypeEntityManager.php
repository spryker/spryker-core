<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Persistence;

use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentType;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStore;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShipmentType\Persistence\ShipmentTypePersistenceFactory getFactory()
 */
class ShipmentTypeEntityManager extends AbstractEntityManager implements ShipmentTypeEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function createShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer
    {
        $shipmentTypeMapper = $this->getFactory()->createShipmentTypeMapper();

        $shipmentTypeEntity = $shipmentTypeMapper->mapShipmentTypeTransferToShipmentTypeEntity(
            $shipmentTypeTransfer,
            new SpyShipmentType(),
        );
        $shipmentTypeEntity->save();

        return $shipmentTypeMapper->mapShipmentTypeEntityToShipmentTypeTransfer($shipmentTypeEntity, $shipmentTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function updateShipmentType(ShipmentTypeTransfer $shipmentTypeTransfer): ShipmentTypeTransfer
    {
        $shipmentTypeEntity = $this->getFactory()
            ->createShipmentTypeQuery()
            ->filterByUuid($shipmentTypeTransfer->getUuidOrFail())
            ->findOne();

        if ($shipmentTypeEntity === null) {
            return $shipmentTypeTransfer;
        }

        $shipmentTypeMapper = $this->getFactory()->createShipmentTypeMapper();

        $shipmentTypeEntity = $shipmentTypeMapper->mapShipmentTypeTransferToShipmentTypeEntity($shipmentTypeTransfer, $shipmentTypeEntity);
        $shipmentTypeEntity->save();

        return $shipmentTypeMapper->mapShipmentTypeEntityToShipmentTypeTransfer($shipmentTypeEntity, $shipmentTypeTransfer);
    }

    /**
     * @param int $idShipmentType
     * @param list<int> $idStores
     *
     * @return void
     */
    public function createShipmentTypeStoreRelations(int $idShipmentType, array $idStores): void
    {
        foreach ($idStores as $idStore) {
            (new SpyShipmentTypeStore())
                ->setFkShipmentType($idShipmentType)
                ->setFkStore($idStore)
                ->save();
        }
    }

    /**
     * @param int $idShipmentType
     * @param list<int> $idStores
     *
     * @return void
     */
    public function deleteShipmentTypeStoreRelations(int $idShipmentType, array $idStores): void
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $shipmentTypeStoreCollection */
        $shipmentTypeStoreCollection = $this->getFactory()
            ->createShipmentTypeStoreQuery()
            ->filterByFkShipmentType($idShipmentType)
            ->filterByFkStore_In($idStores)
            ->find();

        $shipmentTypeStoreCollection->delete();
    }
}
