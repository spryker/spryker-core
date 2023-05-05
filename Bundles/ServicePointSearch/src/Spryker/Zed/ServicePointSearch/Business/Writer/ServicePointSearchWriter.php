<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointSearch\Business\Writer;

use Generated\Shared\Transfer\ServicePointCollectionTransfer;
use Generated\Shared\Transfer\ServicePointConditionsTransfer;
use Generated\Shared\Transfer\ServicePointCriteriaTransfer;
use Generated\Shared\Transfer\ServicePointSearchTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ServicePointSearch\Business\Mapper\ServicePointSearchMapperInterface;
use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToServicePointFacadeInterface;
use Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToStoreFacadeInterface;
use Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface;
use Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchRepositoryInterface;

class ServicePointSearchWriter implements ServicePointSearchWriterInterface
{
    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointAddressTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_SERVICE_POINT_ADDRESS_FK_SERVICE_POINT = 'spy_service_point_address.fk_service_point';

    /**
     * @uses \Orm\Zed\ServicePoint\Persistence\Map\SpyServicePointStoreTableMap::COL_FK_SERVICE_POINT
     *
     * @var string
     */
    protected const COL_SERVICE_POINT_STORE_FK_SERVICE_POINT = 'spy_service_point_store.fk_service_point';

    /**
     * @var \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToServicePointFacadeInterface
     */
    protected ServicePointSearchToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToStoreFacadeInterface
     */
    protected ServicePointSearchToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface
     */
    protected ServicePointSearchToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ServicePointSearch\Business\Mapper\ServicePointSearchMapperInterface
     */
    protected ServicePointSearchMapperInterface $servicePointSearchMapper;

    /**
     * @var \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface
     */
    protected ServicePointSearchEntityManagerInterface $servicePointSearchEntityManager;

    /**
     * @var \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchRepositoryInterface
     */
    protected ServicePointSearchRepositoryInterface $servicePointSearchRepository;

    /**
     * @param \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ServicePointSearch\Dependency\Facade\ServicePointSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ServicePointSearch\Business\Mapper\ServicePointSearchMapperInterface $servicePointSearchMapper
     * @param \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchRepositoryInterface $servicePointSearchRepository
     * @param \Spryker\Zed\ServicePointSearch\Persistence\ServicePointSearchEntityManagerInterface $servicePointSearchEntityManager
     */
    public function __construct(
        ServicePointSearchToServicePointFacadeInterface $servicePointFacade,
        ServicePointSearchToStoreFacadeInterface $storeFacade,
        ServicePointSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ServicePointSearchMapperInterface $servicePointSearchMapper,
        ServicePointSearchRepositoryInterface $servicePointSearchRepository,
        ServicePointSearchEntityManagerInterface $servicePointSearchEntityManager
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->storeFacade = $storeFacade;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->servicePointSearchMapper = $servicePointSearchMapper;
        $this->servicePointSearchEntityManager = $servicePointSearchEntityManager;
        $this->servicePointSearchRepository = $servicePointSearchRepository;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointEvents(array $eventTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferIds($eventTransfers);

        $this->writeCollectionByServicePointIds(array_unique($servicePointIds));
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointAddressEvents(array $eventTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::COL_SERVICE_POINT_ADDRESS_FK_SERVICE_POINT,
        );

        $this->writeCollectionByServicePointIds(array_unique($servicePointIds));
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     *
     * @return void
     */
    public function writeCollectionByServicePointStoreEvents(array $eventTransfers): void
    {
        $servicePointIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventTransfers,
            static::COL_SERVICE_POINT_STORE_FK_SERVICE_POINT,
        );

        $this->writeCollectionByServicePointIds(array_unique($servicePointIds));
    }

    /**
     * @param list<int> $servicePointIds
     *
     * @return void
     */
    protected function writeCollectionByServicePointIds(array $servicePointIds): void
    {
        if (!$servicePointIds) {
            return;
        }

        $servicePointCollectionTransfer = $this->getServicePointsByServicePointIds($servicePointIds);
        if (!$servicePointCollectionTransfer->getServicePoints()->count()) {
            $this->servicePointSearchEntityManager->deleteServicePointSearchByServicePointIds($servicePointIds);

            return;
        }

        $servicePointSearchTransfers = $this->getServicePointSearchTransfersIndexedByIdServicePointAndStoreName(
            $this->servicePointSearchRepository->getServicePointSearchTransfersByServicePointIds($servicePointIds),
        );

        $this->writeCollection(
            $servicePointCollectionTransfer->getServicePoints()->getArrayCopy(),
            $servicePointSearchTransfers,
        );
    }

    /**
     * @param list<int> $servicePointIds
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    protected function getServicePointsByServicePointIds(array $servicePointIds): ServicePointCollectionTransfer
    {
        $servicePointConditionsTransfer = (new ServicePointConditionsTransfer())
            ->setWithAddressRelation(true)
            ->setWithStoreRelations(true)
            ->setServicePointIds($servicePointIds);

        $servicePointCriteriaTransfer = (new ServicePointCriteriaTransfer())
            ->setServicePointConditions($servicePointConditionsTransfer);

        return $this->servicePointFacade->getServicePointCollection($servicePointCriteriaTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointTransfer> $servicePointTransfers
     * @param array<int, array<string, \Generated\Shared\Transfer\ServicePointSearchTransfer>> $servicePointSearchTransfers
     *
     * @return void
     */
    protected function writeCollection(array $servicePointTransfers, array $servicePointSearchTransfers): void
    {
        $inactiveServicePointIds = [];
        $storeTransfers = $this->storeFacade->getAllStores();

        foreach ($servicePointTransfers as $servicePointTransfer) {
            if (!$servicePointTransfer->getIsActive()) {
                $inactiveServicePointIds[] = $servicePointTransfer->getIdServicePointOrFail();

                continue;
            }

            $this->writeCollectionPerStore($servicePointTransfer, $servicePointSearchTransfers, $storeTransfers);
        }

        $this->servicePointSearchEntityManager->deleteServicePointSearchByServicePointIds($inactiveServicePointIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param array<int, array<string, \Generated\Shared\Transfer\ServicePointSearchTransfer>> $servicePointSearchTransfers
     * @param list<\Generated\Shared\Transfer\StoreTransfer> $storeTransfers
     *
     * @return void
     */
    protected function writeCollectionPerStore(
        ServicePointTransfer $servicePointTransfer,
        array $servicePointSearchTransfers,
        array $storeTransfers
    ): void {
        $idServicePoint = $servicePointTransfer->getIdServicePointOrFail();

        foreach ($storeTransfers as $storeTransfer) {
            if (!$this->isServicePointAvailableInStore($servicePointTransfer, $storeTransfer)) {
                continue;
            }

            $storeName = $storeTransfer->getNameOrFail();
            $servicePointSearchTransfer = $servicePointSearchTransfers[$idServicePoint][$storeName] ?? new ServicePointSearchTransfer();

            $servicePointSearchTransfer = $this->servicePointSearchMapper->mapServicePointTransferToServicePointSearchTransfer(
                $servicePointTransfer,
                $servicePointSearchTransfer,
                $storeTransfer,
            );

            $this->servicePointSearchEntityManager->saveServicePointSearch($servicePointSearchTransfer);

            if (isset($servicePointSearchTransfers[$idServicePoint][$storeName])) {
                unset($servicePointSearchTransfers[$idServicePoint][$storeName]);
            }
        }

        if (isset($servicePointSearchTransfers[$idServicePoint])) {
            $this->deleteServicePointSearchTransfers($servicePointSearchTransfers[$idServicePoint]);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     *
     * @return array<int, array<string, \Generated\Shared\Transfer\ServicePointSearchTransfer>>
     */
    protected function getServicePointSearchTransfersIndexedByIdServicePointAndStoreName(array $servicePointSearchTransfers): array
    {
        $indexedServicePointSearchTransfers = [];

        foreach ($servicePointSearchTransfers as $servicePointSearchTransfer) {
            $idServicePoint = $servicePointSearchTransfer->getIdServicePointOrFail();
            $storeName = $servicePointSearchTransfer->getStoreOrFail();

            $indexedServicePointSearchTransfers[$idServicePoint][$storeName] = $servicePointSearchTransfer;
        }

        return $indexedServicePointSearchTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    protected function isServicePointAvailableInStore(ServicePointTransfer $servicePointTransfer, StoreTransfer $storeTransfer): bool
    {
        if (!$servicePointTransfer->getStoreRelation()) {
            return false;
        }

        foreach ($servicePointTransfer->getStoreRelationOrFail()->getStores() as $servicePointStoreTransfer) {
            if ($servicePointStoreTransfer->getNameOrFail() === $storeTransfer->getNameOrFail()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     *
     * @return void
     */
    protected function deleteServicePointSearchTransfers(array $servicePointSearchTransfers): void
    {
        if (!$servicePointSearchTransfers) {
            return;
        }

        $servicePointSearchIds = $this->extractServicePointSearchIds($servicePointSearchTransfers);
        $this->servicePointSearchEntityManager->deleteServicePointSearchByServicePointSearchIds($servicePointSearchIds);
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ServicePointSearchTransfer> $servicePointSearchTransfers
     *
     * @return list<int>
     */
    protected function extractServicePointSearchIds(array $servicePointSearchTransfers): array
    {
        $servicePointSearchIds = [];

        foreach ($servicePointSearchTransfers as $servicePointSearchTransfer) {
            $servicePointSearchIds[] = $servicePointSearchTransfer->getIdServicePointSearchOrFail();
        }

        return $servicePointSearchIds;
    }
}
