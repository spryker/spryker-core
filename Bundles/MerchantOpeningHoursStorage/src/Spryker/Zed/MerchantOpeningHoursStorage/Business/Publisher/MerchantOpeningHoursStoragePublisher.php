<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Business\Publisher;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;
use Orm\Zed\MerchantOpeningHours\Persistence\Map\SpyMerchantOpeningHoursDateScheduleTableMap;
use Orm\Zed\MerchantOpeningHours\Persistence\Map\SpyMerchantOpeningHoursWeekdayScheduleTableMap;
use Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToEventBehaviorFacadeInterface;
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
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface $merchantOpeningHoursStorageEntityManager
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface $merchantOpeningHoursStorageRepository
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     */
    public function __construct(
        MerchantOpeningHoursStorageEntityManagerInterface $merchantOpeningHoursStorageEntityManager,
        MerchantOpeningHoursStorageRepositoryInterface $merchantOpeningHoursStorageRepository,
        MerchantOpeningHoursStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
    ) {
        $this->merchantOpeningHoursStorageEntityManager = $merchantOpeningHoursStorageEntityManager;
        $this->merchantOpeningHoursStorageRepository = $merchantOpeningHoursStorageRepository;
        $this->eventBehaviorFacade = $eventBehaviorFacade;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function publish(array $merchantIds): void
    {
        foreach ($merchantIds as $idMerchant) {
            $weekdayScheduleTransfers = $this->merchantOpeningHoursStorageRepository->getMerchantOpeningHoursWeekdayScheduleByFkMerchant($idMerchant);
            $dateScheduleTransfers = $this->merchantOpeningHoursStorageRepository->getMerchantOpeningHoursDateScheduleByFkMerchant($idMerchant);

            if ($weekdayScheduleTransfers->count() > 0 || $dateScheduleTransfers->count() > 0) {
                $merchantOpenHoursStorageTransfer = $this->createMerchantOpeningHoursStorageTransfer($weekdayScheduleTransfers, $dateScheduleTransfers);
                $this->merchantOpeningHoursStorageEntityManager->saveMerchantOpenHoursStorage($merchantOpenHoursStorageTransfer, $idMerchant);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function publishWeekdayScheduleCreate(array $eventEntityTransfers): void
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
    public function publishDateScheduleCreate(array $eventEntityTransfers): void
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
    public function publishMerchantOpeningHours(array $eventEntityTransfers): void
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
}
