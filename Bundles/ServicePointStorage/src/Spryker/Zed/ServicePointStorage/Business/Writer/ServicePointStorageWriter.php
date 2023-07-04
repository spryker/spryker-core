<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointStorageTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ServicePointStorage\Business\Mapper\ServicePointStorageMapperInterface;
use Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToServicePointFacadeInterface;
use Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToStoreFacadeInterface;
use Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface;

class ServicePointStorageWriter implements ServicePointStorageWriterInterface
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointAddressTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT = 'spy_service_point_address.fk_service_point';

    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointStoreTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_POINT_STORE_COL_FK_SERVICE_POINT = 'spy_service_point_store.fk_service_point';

    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServiceTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const SERVICE_COL_FK_SERVICE_POINT = 'spy_service.fk_service_point';

    /**
     * @var \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToEventBehaviorFacadeInterface
     */
    protected ServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToServicePointFacadeInterface
     */
    protected ServicePointStorageToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToStoreFacadeInterface
     */
    protected ServicePointStorageToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface
     */
    protected ServicePointStorageEntityManagerInterface $servicePointStorageEntityManager;

    /**
     * @var \Spryker\Zed\ServicePointStorage\Business\Mapper\ServicePointStorageMapperInterface
     */
    protected ServicePointStorageMapperInterface $servicePointStorageMapper;

    /**
     * @param \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface $servicePointStorageEntityManager
     * @param \Spryker\Zed\ServicePointStorage\Business\Mapper\ServicePointStorageMapperInterface $servicePointStorageMapper
     */
    public function __construct(
        ServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ServicePointStorageToServicePointFacadeInterface $servicePointFacade,
        ServicePointStorageToStoreFacadeInterface $storeFacade,
        ServicePointStorageEntityManagerInterface $servicePointStorageEntityManager,
        ServicePointStorageMapperInterface $servicePointStorageMapper
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->servicePointFacade = $servicePointFacade;
        $this->storeFacade = $storeFacade;
        $this->servicePointStorageEntityManager = $servicePointStorageEntityManager;
        $this->servicePointStorageMapper = $servicePointStorageMapper;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointEvents(array $eventEntityTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->writeServicePointStorageCollection($servicePointIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointAddressEvents(array $eventEntityTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::SERVICE_POINT_ADDRESS_COL_FK_SERVICE_POINT,
        );

        $this->writeServicePointStorageCollection($servicePointIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServicePointStoreEvents(array $eventEntityTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::SERVICE_POINT_STORE_COL_FK_SERVICE_POINT,
        );

        $this->writeServicePointStorageCollection($servicePointIds);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServicePointStorageCollectionByServiceEvents(array $eventEntityTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::SERVICE_COL_FK_SERVICE_POINT,
        );

        $this->writeServicePointStorageCollection($servicePointIds);
    }

    /**
     * @param list<int> $servicePointIds
     *
     * @return void
     */
    protected function writeServicePointStorageCollection(array $servicePointIds): void
    {
        /** @var list<int> $servicePointIds */
        $servicePointIds = array_filter($servicePointIds);
        if (!$servicePointIds) {
            return;
        }

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())->setServicePointConditions(
            (new ServicePointConditionsTransfer())
                ->setServicePointIds($servicePointIds)
                ->setWithStoreRelations(true)
                ->setWithServiceRelations(true)
                ->setWithAddressRelation(true),
        );

        $servicePointCollectionTransfer = $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);
        if (!count($servicePointCollectionTransfer->getServicePoints())) {
            $this->servicePointStorageEntityManager->deleteServicePointStorageByServicePointIds($servicePointIds);

            return;
        }

        $storeTransfers = $this->storeFacade->getAllStores();
        foreach ($servicePointCollectionTransfer->getServicePoints() as $servicePointTransfer) {
            $this->writeCollectionPerStore($servicePointTransfer, $storeTransfers);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    protected function writeCollectionPerStore(
        ServicePointTransfer $servicePointTransfer,
        array $storeTransfers
    ): void {
        $idServicePoint = $servicePointTransfer->getIdServicePointOrFail();
        if (!$servicePointTransfer->getIsActive() || !$servicePointTransfer->getStoreRelation()) {
            $this->servicePointStorageEntityManager->deleteServicePointStorageByServicePointIds([$idServicePoint]);

            return;
        }

        $this->filterOutInactiveServices($servicePointTransfer);

        foreach ($storeTransfers as $storeTransfer) {
            if (!$this->isServicePointAvailableInStore($servicePointTransfer, $storeTransfer)) {
                $this->servicePointStorageEntityManager->deleteServicePointStorageByServicePointIds(
                    [$idServicePoint],
                    $storeTransfer->getNameOrFail(),
                );

                continue;
            }

            $servicePointStorageTransfer = $this->servicePointStorageMapper->mapServicePointTransferToServicePointStorageTransfer(
                $servicePointTransfer,
                new ServicePointStorageTransfer(),
            );

            $this->servicePointStorageEntityManager->saveServicePointStorageForStore($servicePointStorageTransfer, $storeTransfer->getNameOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isServicePointAvailableInStore(ServicePointTransfer $servicePointTransfer, StoreTransfer $storeTransfer): bool
    {
        foreach ($servicePointTransfer->getStoreRelationOrFail()->getStores() as $servicePointStoreTransfer) {
            if ($servicePointStoreTransfer->getIdStoreOrFail() === $storeTransfer->getIdStoreOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return void
     */
    protected function filterOutInactiveServices(ServicePointTransfer $servicePointTransfer): void
    {
        $activeServiceTransfers = new ArrayObject();
        foreach ($servicePointTransfer->getServices() as $serviceTransfer) {
            if ($serviceTransfer->getIsActive()) {
                $activeServiceTransfers->append($serviceTransfer);
            }
        }

        $servicePointTransfer->setServices($activeServiceTransfers);
    }
}
