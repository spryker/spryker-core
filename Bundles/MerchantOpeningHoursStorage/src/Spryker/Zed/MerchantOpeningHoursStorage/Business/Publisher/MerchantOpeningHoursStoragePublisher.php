<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Business\Publisher;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Orm\Zed\MerchantOpeningHours\Persistence\Map\SpyMerchantOpeningHoursDateScheduleTableMap;
use Orm\Zed\MerchantOpeningHours\Persistence\Map\SpyMerchantOpeningHoursWeekdayScheduleTableMap;
use Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToMerchantFacadeInterface;
use Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface;
use Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface;

class MerchantOpeningHoursStoragePublisher implements MerchantOpeningHoursStoragePublisherInterface
{
    /**
     * @var \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface
     */
    protected $merchantOpeningHoursStorageEntityManager;

    /**
     * @var \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface
     */
    protected $merchantOpeningHoursStorageRepository;

    /**
     * @var \Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface $merchantOpeningHoursStorageEntityManager
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface $merchantOpeningHoursStorageRepository
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantOpeningHoursStorageEntityManagerInterface $merchantOpeningHoursStorageEntityManager,
        MerchantOpeningHoursStorageRepositoryInterface $merchantOpeningHoursStorageRepository,
        MerchantOpeningHoursStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        MerchantOpeningHoursStorageToMerchantFacadeInterface $merchantFacade
    ) {
        $this->merchantOpeningHoursStorageEntityManager = $merchantOpeningHoursStorageEntityManager;
        $this->merchantOpeningHoursStorageRepository = $merchantOpeningHoursStorageRepository;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function publish(array $merchantIds): void
    {
        $merchantCollectionTransfer = $this->getMerchants($merchantIds);

        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $weekdayScheduleTransfers = $this->merchantOpeningHoursStorageRepository
                ->getMerchantOpeningHoursWeekdayScheduleByFkMerchant($merchantTransfer->getIdMerchant());
            $dateScheduleTransfers = $this->merchantOpeningHoursStorageRepository
                ->getMerchantOpeningHoursDateScheduleByFkMerchant($merchantTransfer->getIdMerchant());

            if ($weekdayScheduleTransfers->count() > 0 || $dateScheduleTransfers->count() > 0) {
                $merchantOpenHoursStorageTransfer = $this->createMerchantOpeningHoursStorageTransfer(
                    $weekdayScheduleTransfers,
                    $dateScheduleTransfers
                );
                $this->merchantOpeningHoursStorageEntityManager->saveMerchantOpenHoursStorage(
                    $merchantOpenHoursStorageTransfer,
                    $merchantTransfer->getMerchantReference()
                );
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeMerchantOpenHoursStorageByMerchantOpeningHoursWeekdayScheduleCreateEvents(array $eventEntityTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyMerchantOpeningHoursWeekdayScheduleTableMap::COL_FK_MERCHANT
        );

        $this->publish($merchantIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeMerchantOpenHoursStorageByMerchantOpeningHoursDateScheduleCreateEvents(array $eventEntityTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyMerchantOpeningHoursDateScheduleTableMap::COL_FK_MERCHANT
        );

        $this->publish($merchantIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeMerchantOpenHoursStorageByMerchantOpeningHoursPublishEvents(array $eventEntityTransfers): void
    {
        $merchantIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->publish($merchantIds);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\WeekdayScheduleTransfer[] $weekdayScheduleTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\DateScheduleTransfer[] $dateScheduleTransfers
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer
     */
    protected function createMerchantOpeningHoursStorageTransfer(
        ArrayObject $weekdayScheduleTransfers,
        ArrayObject $dateScheduleTransfers
    ): MerchantOpeningHoursStorageTransfer {
        return (new MerchantOpeningHoursStorageTransfer())
            ->setWeekdaySchedule($weekdayScheduleTransfers)
            ->setDateSchedule($dateScheduleTransfers);
    }

    /**
     * @param array $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function getMerchants(array $merchantIds): MerchantCollectionTransfer
    {
        return $this->merchantFacade->get(
            (new MerchantCriteriaTransfer())->setMerchantIds($merchantIds)
        );
    }
}
