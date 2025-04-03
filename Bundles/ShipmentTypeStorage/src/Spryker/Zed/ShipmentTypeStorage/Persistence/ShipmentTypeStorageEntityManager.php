<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Persistence;

use Generated\Shared\Transfer\ShipmentTypeListStorageTransfer;
use Generated\Shared\Transfer\ShipmentTypeStorageTransfer;
use Orm\Zed\ShipmentTypeStorage\Persistence\Base\SpyShipmentTypeListStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStoragePersistenceFactory getFactory()
 */
class ShipmentTypeStorageEntityManager extends AbstractEntityManager implements ShipmentTypeStorageEntityManagerInterface
{
    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @var string
     */
    protected const TABLE_NAME_SHIPMENT_TYPE_LIST_STORAGE = 'spy_shipment_type_list_storage';

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeListStorageTransfer $shipmentTypeListStorageTransfer
     * @param string $storeName
     *
     * @return void
     */
    public function saveShipmentTypeListStorage(ShipmentTypeListStorageTransfer $shipmentTypeListStorageTransfer, string $storeName): void
    {
        if (!$this->isShipmentTypeListStorageTableExists()) {
            return;
        }

        $shipmentTypeListStorageEntity = $this->getFactory()
            ->createShipmentTypeListStorageQuery()
            ->filterByStore($storeName)
            ->findOneOrCreate();

        $shipmentTypeListStorageEntity->setData($shipmentTypeListStorageTransfer->toArray());
        $shipmentTypeListStorageEntity->save();
    }

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

    /**
     * @deprecated Exists for BC reasons. Will be removed in the next major release.
     *
     * @return bool
     */
    public function isShipmentTypeListStorageTableExists(): bool
    {
        if (
            !class_exists(SpyShipmentTypeListStorageQuery::class) ||
            !$this->getFactory()->getPropelFacade()->tableExists(static::TABLE_NAME_SHIPMENT_TYPE_LIST_STORAGE)
        ) {
            return false;
        }

        return true;
    }
}
