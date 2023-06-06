<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeStorage\Business\Writer;

use Generated\Shared\Transfer\ShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreCriteriaTransfer;
use Spryker\Zed\ShipmentTypeStorage\Business\Mapper\ShipmentTypeStorageMapperInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeInterface;
use Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageEntityManagerInterface;

class ShipmentTypeStorageWriter implements ShipmentTypeStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\ShipmentType\Persistence\Map\SpyShipmentTypeStoreTableMap::COL_FK_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const COL_FK_SHIPMENT_TYPE = 'spy_shipment_type_store.fk_shipment_type';

    /**
     * @var \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageEntityManagerInterface
     */
    protected ShipmentTypeStorageEntityManagerInterface $shipmentTypeStorageEntityManager;

    /**
     * @var \Spryker\Zed\ShipmentTypeStorage\Business\Mapper\ShipmentTypeStorageMapperInterface
     */
    protected ShipmentTypeStorageMapperInterface $shipmentTypeStorageMapper;

    /**
     * @var \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface
     */
    protected ShipmentTypeStorageToShipmentTypeFacadeInterface $shipmentTypeFacade;

    /**
     * @var \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeInterface
     */
    protected ShipmentTypeStorageToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToEventBehaviorFacadeInterface
     */
    protected ShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeStorage\Persistence\ShipmentTypeStorageEntityManagerInterface $shipmentTypeStorageEntityManager
     * @param \Spryker\Zed\ShipmentTypeStorage\Business\Mapper\ShipmentTypeStorageMapperInterface $shipmentTypeStorageMapper
     * @param \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToShipmentTypeFacadeInterface $shipmentTypeFacade
     * @param \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ShipmentTypeStorage\Dependency\Facade\ShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct(
        ShipmentTypeStorageEntityManagerInterface $shipmentTypeStorageEntityManager,
        ShipmentTypeStorageMapperInterface $shipmentTypeStorageMapper,
        ShipmentTypeStorageToShipmentTypeFacadeInterface $shipmentTypeFacade,
        ShipmentTypeStorageToStoreFacadeInterface $storeFacade,
        ShipmentTypeStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
    ) {
        $this->shipmentTypeStorageEntityManager = $shipmentTypeStorageEntityManager;
        $this->shipmentTypeStorageMapper = $shipmentTypeStorageMapper;
        $this->shipmentTypeFacade = $shipmentTypeFacade;
        $this->storeFacade = $storeFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeEvents(array $eventEntityTransfers): void
    {
        $shipmentTypeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeShipmentTypeCollection(array_unique($shipmentTypeIds));
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeShipmentTypeStorageCollectionByShipmentTypeStoreEvents(array $eventEntityTransfers): void
    {
        $shipmentTypeIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, static::COL_FK_SHIPMENT_TYPE);

        $this->writeShipmentTypeCollection(array_unique($shipmentTypeIds));
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return void
     */
    protected function writeShipmentTypeCollection(array $shipmentTypeIds): void
    {
        if ($shipmentTypeIds === []) {
            return;
        }

        $shipmentTypeCriteriaTransfer = $this->createShipmentTypeCriteriaTransfer($shipmentTypeIds);
        $shipmentTypeCollectionTransfer = $this->shipmentTypeFacade->getShipmentTypeCollection($shipmentTypeCriteriaTransfer);
        if ($shipmentTypeCollectionTransfer->getShipmentTypes()->count() === 0) {
            $this->shipmentTypeStorageEntityManager->deleteShipmentTypeStorageByShipmentTypeIds($shipmentTypeIds);
        }
        $storeCollectionTransfer = $this->storeFacade->getStoreCollection(new StoreCriteriaTransfer());

        foreach ($storeCollectionTransfer->getStores() as $storeTransfer) {
            $storeName = $storeTransfer->getNameOrFail();
            [$shipmentTypeTransfersWithStoreRelation, $shipmentTypeTransfersWithoutStoreRelation] = $this->splitShipmentTypeTransfersByStoreRelation(
                $shipmentTypeCollectionTransfer,
                $storeName,
            );

            if ($shipmentTypeTransfersWithoutStoreRelation !== []) {
                $this->shipmentTypeStorageEntityManager->deleteShipmentTypeStorageByShipmentTypeIds(
                    array_keys($shipmentTypeTransfersWithoutStoreRelation),
                    $storeName,
                );
            }

            $shipmentTypeStorageTransfers = $this->shipmentTypeStorageMapper->mapShipmentTypeTransfersToShipmentTypeStorageTransfers(
                $shipmentTypeTransfersWithStoreRelation,
                [],
            );

            $this->writeShipmentTypeStorageTransfersForStore($shipmentTypeStorageTransfers, $storeName);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer
     * @param string $storeName
     *
     * @return list<array<int, \Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    protected function splitShipmentTypeTransfersByStoreRelation(
        ShipmentTypeCollectionTransfer $shipmentTypeCollectionTransfer,
        string $storeName
    ): array {
        $shipmentTypeTransfersWithStoreRelation = [];
        $shipmentTypeTransfersWithoutStoreRelation = [];
        foreach ($shipmentTypeCollectionTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
            if (!$this->isShipmentTypeHasStoreRelation($shipmentTypeTransfer, $storeName)) {
                $shipmentTypeTransfersWithoutStoreRelation[$shipmentTypeTransfer->getIdShipmentTypeOrFail()] = $shipmentTypeTransfer;

                continue;
            }

            $shipmentTypeTransfersWithStoreRelation[$shipmentTypeTransfer->getIdShipmentTypeOrFail()] = $shipmentTypeTransfer;
        }

        return [$shipmentTypeTransfersWithStoreRelation, $shipmentTypeTransfersWithoutStoreRelation];
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer $shipmentTypeTransfer
     * @param string $storeName
     *
     * @return bool
     */
    protected function isShipmentTypeHasStoreRelation(ShipmentTypeTransfer $shipmentTypeTransfer, string $storeName): bool
    {
        if (!$shipmentTypeTransfer->getStoreRelation()) {
            return false;
        }

        foreach ($shipmentTypeTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
            if ($storeTransfer->getNameOrFail() === $storeName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ShipmentTypeStorageTransfer> $shipmentTypeStorageTransfers
     * @param string $storeName
     *
     * @return void
     */
    protected function writeShipmentTypeStorageTransfersForStore(array $shipmentTypeStorageTransfers, string $storeName): void
    {
        foreach ($shipmentTypeStorageTransfers as $shipmentTypeStorageTransfer) {
            $this->shipmentTypeStorageEntityManager->saveShipmentTypeStorage($shipmentTypeStorageTransfer, $storeName);
        }
    }

    /**
     * @param list<int> $shipmentTypeIds
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeCriteriaTransfer
     */
    protected function createShipmentTypeCriteriaTransfer(array $shipmentTypeIds): ShipmentTypeCriteriaTransfer
    {
        $shipmentTypeConditionsTransfer = (new ShipmentTypeConditionsTransfer())
            ->setShipmentTypeIds($shipmentTypeIds)
            ->setIsActive(true)
            ->setWithStoreRelations(true);

        return (new ShipmentTypeCriteriaTransfer())->setShipmentTypeConditions($shipmentTypeConditionsTransfer);
    }
}
