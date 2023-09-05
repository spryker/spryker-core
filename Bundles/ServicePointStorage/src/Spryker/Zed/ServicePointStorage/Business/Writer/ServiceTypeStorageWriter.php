<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointStorage\Business\Writer;

use Generated\Shared\Transfer\ServiceTypeCollectionTransfer;
use Generated\Shared\Transfer\ServiceTypeConditionsTransfer;
use Generated\Shared\Transfer\ServiceTypeCriteriaTransfer;
use Generated\Shared\Transfer\ServiceTypeStorageTransfer;
use Spryker\Zed\ServicePointStorage\Business\Mapper\ServicePointStorageMapperInterface;
use Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToServicePointFacadeInterface;
use Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface;

class ServiceTypeStorageWriter implements ServiceTypeStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToEventBehaviorFacadeInterface
     */
    protected ServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ServicePointStorage\Dependency\Facade\ServicePointStorageToServicePointFacadeInterface
     */
    protected ServicePointStorageToServicePointFacadeInterface $servicePointFacade;

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
     * @param \Spryker\Zed\ServicePointStorage\Persistence\ServicePointStorageEntityManagerInterface $servicePointStorageEntityManager
     * @param \Spryker\Zed\ServicePointStorage\Business\Mapper\ServicePointStorageMapperInterface $servicePointStorageMapper
     */
    public function __construct(
        ServicePointStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ServicePointStorageToServicePointFacadeInterface $servicePointFacade,
        ServicePointStorageEntityManagerInterface $servicePointStorageEntityManager,
        ServicePointStorageMapperInterface $servicePointStorageMapper
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->servicePointFacade = $servicePointFacade;
        $this->servicePointStorageEntityManager = $servicePointStorageEntityManager;
        $this->servicePointStorageMapper = $servicePointStorageMapper;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeServiceTypeStorageCollectionByServiceTypeEvents(array $eventEntityTransfers): void
    {
        $serviceTypeIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        $serviceTypeIds = array_unique(array_filter($serviceTypeIds));

        if ($serviceTypeIds === []) {
            return;
        }

        $serviceTypeCollectionTransfer = $this->getServiceTypeCollection($serviceTypeIds);

        if ($serviceTypeCollectionTransfer->getServiceTypes()->count() === 0) {
            $this->servicePointStorageEntityManager->deleteServiceTypeStorageByServiceTypeIds($serviceTypeIds);

            return;
        }

        $retrievedServiceTypeIds = [];
        foreach ($serviceTypeCollectionTransfer->getServiceTypes() as $serviceTypeTransfer) {
            $serviceTypeStorageTransfer = $this->servicePointStorageMapper
                ->mapServiceTypeTransferToServiceTypeStorageTransfer(
                    $serviceTypeTransfer,
                    new ServiceTypeStorageTransfer(),
                );

            $this->servicePointStorageEntityManager->saveServiceTypeStorage($serviceTypeStorageTransfer);

            $retrievedServiceTypeIds[] = $serviceTypeTransfer->getIdServiceTypeOrFail();
        }

        $serviceTypeIdsToDelete = array_diff($serviceTypeIds, $retrievedServiceTypeIds);
        if ($serviceTypeIdsToDelete !== []) {
            $this->servicePointStorageEntityManager->deleteServiceTypeStorageByServiceTypeIds($serviceTypeIdsToDelete);
        }
    }

    /**
     * @param list<int> $serviceTypeIds
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionTransfer
     */
    protected function getServiceTypeCollection(array $serviceTypeIds): ServiceTypeCollectionTransfer
    {
        $serviceTypeCriteriaTransfer = (new ServiceTypeCriteriaTransfer())->setServiceTypeConditions(
            (new ServiceTypeConditionsTransfer())->setServiceTypeIds($serviceTypeIds),
        );

        return $this->servicePointFacade->getServiceTypeCollection($serviceTypeCriteriaTransfer);
    }
}
