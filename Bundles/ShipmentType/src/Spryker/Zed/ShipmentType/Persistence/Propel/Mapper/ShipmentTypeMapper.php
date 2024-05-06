<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentType\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentType;
use Orm\Zed\Store\Persistence\SpyStore;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;

class ShipmentTypeMapper
{
    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param \Orm\Zed\ShipmentType\Persistence\SpyShipmentType $shipmentTypeEntity
     *
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentType
     */
    public function mapShipmentTypeTransferToShipmentTypeEntity(
        ShipmentTypeTransfer $shipmentTypeTransfer,
        SpyShipmentType $shipmentTypeEntity
    ): SpyShipmentType {
        return $shipmentTypeEntity->fromArray($shipmentTypeTransfer->modifiedToArray());
    }

    /**
     * @param \Orm\Zed\ShipmentType\Persistence\SpyShipmentType $shipmentTypeEntity
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function mapShipmentTypeEntityToShipmentTypeTransfer(
        SpyShipmentType $shipmentTypeEntity,
        ShipmentTypeTransfer $shipmentTypeTransfer
    ): ShipmentTypeTransfer {
        return $shipmentTypeTransfer->fromArray($shipmentTypeEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ShipmentType\Persistence\SpyShipmentType> $shipmentTypeEntityCollection
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer
     */
    public function mapShipmentTypeEntityCollectionToShipmentTypeCollectionTransfer(
        ObjectCollection $shipmentTypeEntityCollection,
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
    ): ShipmentTypeCollectionTransfer {
        foreach ($shipmentTypeEntityCollection as $shipmentTypeEntity) {
            $shipmentTypeCollectionTransfer->addShipmentType($this->mapShipmentTypeEntityToShipmentTypeTransfer($shipmentTypeEntity, new ShipmentTypeTransfer()));
        }

        return $shipmentTypeCollectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<array-key, \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeStore> $shipmentTypeStoreEntities
     * @param array<int, \Generated\Shared\Transfer\StoreRelationTransfer> $storeRelationTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\StoreRelationTransfer>
     */
    public function mapShipmentTypeStoreEntitiesToStoreRelationTransfersIndexedByIdShipmentStore(
        Collection $shipmentTypeStoreEntities,
        array $storeRelationTransfers
    ): array {
        foreach ($shipmentTypeStoreEntities as $shipmentTypeStoreEntity) {
            $idShipmentStore = $shipmentTypeStoreEntity->getFkShipmentType();
            $storeRelationTransfer = $storeRelationTransfers[$idShipmentStore] ?? new StoreRelationTransfer();

            $storeTransfer = $this->mapStoreEntityToStoreTransfer($shipmentTypeStoreEntity->getStore(), new StoreTransfer());
            $storeRelationTransfer
                ->setIdEntity($idShipmentStore)
                ->addStores($storeTransfer);

            $storeRelationTransfers[$idShipmentStore] = $storeRelationTransfer;
        }

        return $storeRelationTransfers;
    }

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStore $storeEntity
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function mapStoreEntityToStoreTransfer(SpyStore $storeEntity, StoreTransfer $storeTransfer): StoreTransfer
    {
        return $storeTransfer->fromArray($storeEntity->toArray(), true);
    }
}
