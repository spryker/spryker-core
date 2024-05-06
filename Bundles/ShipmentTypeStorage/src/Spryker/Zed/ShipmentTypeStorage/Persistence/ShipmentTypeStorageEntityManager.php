<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStoragePersistenceFactory getFactory()
 */
class ShipmentTypeStorageEntityManager extends AbstractEntityManager implements ShipmentTypeStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveShipmentTypeStorage(ShipmentTypeStorageTransfer $shipmentTypeStorageTransfer, string $storeName): void
    {
        $shipmentTypeStorageEntity = $this->getFactory()
            ->createShipmentTypeStorageQuery()
            ->filterByFkShipmentType($shipmentTypeStorageTransfer->getIdShipmentTypeOrFail())
            ->filterByStore($storeName)
            ->findOneOrCreate();

        $shipmentTypeStorageEntity->setData($shipmentTypeStorageTransfer->toArray());
        $shipmentTypeStorageEntity->save();
    }

    /**
     * @param list<int> $shipmentTypeIds
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteShipmentTypeStorageByShipmentTypeIds(array $shipmentTypeIds, ?string $storeName = null): void
    {
        $shipmentTypeStorageQuery = $this->getFactory()
            ->createShipmentTypeStorageQuery()
            ->filterByFkShipmentType_In($shipmentTypeIds);

        if ($storeName) {
            $shipmentTypeStorageQuery->filterByStore($storeName);
        }

        /** @var \Propel\Runtime\Collection\ObjectCollection $shipmentTypeStorageCollection */
        $shipmentTypeStorageCollection = $shipmentTypeStorageQuery->find();
        $shipmentTypeStorageCollection->delete();
    }
}
