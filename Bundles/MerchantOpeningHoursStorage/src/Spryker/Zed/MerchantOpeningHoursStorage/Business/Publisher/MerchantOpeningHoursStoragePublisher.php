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
     * @var \Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface $merchantOpeningHoursStorageEntityManager
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface $merchantOpeningHoursStorageRepository
     * @param \Spryker\Zed\MerchantOpeningHoursStorage\Dependency\Facade\MerchantOpeningHoursStorageToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantOpeningHoursStorageEntityManagerInterface $merchantOpeningHoursStorageEntityManager,
        MerchantOpeningHoursStorageRepositoryInterface $merchantOpeningHoursStorageRepository,
        MerchantOpeningHoursStorageToMerchantFacadeInterface $merchantFacade
    ) {
        $this->merchantOpeningHoursStorageEntityManager = $merchantOpeningHoursStorageEntityManager;
        $this->merchantOpeningHoursStorageRepository = $merchantOpeningHoursStorageRepository;
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
        foreach ($merchantIds as $idMerchant) {
            $weekdayScheduleTransfers = $this->merchantOpeningHoursStorageRepository->getMerchantOpeningHoursWeekdayScheduleByFkMerchant($idMerchant);
            $dateScheduleTransfers = $this->merchantOpeningHoursStorageRepository->getMerchantOpeningHoursDateScheduleByFkMerchant($idMerchant);

            if ($weekdayScheduleTransfers->count() > 0 || $dateScheduleTransfers->count() > 0) {
                $merchantReference = $this->findMerchantReferenceByIdMerchant($merchantCollectionTransfer, $idMerchant);

                if (!$merchantReference) {
                    continue;
                }

                $merchantOpenHoursStorageTransfer = $this->createMerchantOpeningHoursStorageTransfer($weekdayScheduleTransfers, $dateScheduleTransfers);
                $this->merchantOpeningHoursStorageEntityManager->saveMerchantOpenHoursStorage($merchantOpenHoursStorageTransfer, $merchantReference);
            }
        }
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

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     * @param int $idMerchant
     *
     * @return string|null
     */
    protected function findMerchantReferenceByIdMerchant(MerchantCollectionTransfer $merchantCollectionTransfer, int $idMerchant): ?string
    {
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            if ($merchantTransfer->getIdMerchant() === $idMerchant) {
                return $merchantTransfer->getMerchantReference();
            }
        }

        return null;
    }
}
