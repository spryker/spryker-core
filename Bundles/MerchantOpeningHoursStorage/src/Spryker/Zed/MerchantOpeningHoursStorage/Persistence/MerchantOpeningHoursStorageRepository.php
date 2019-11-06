<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\DateScheduleTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\WeekdayScheduleTransfer;
use Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule;
use Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStoragePersistenceFactory getFactory()
 */
class MerchantOpeningHoursStorageRepository extends AbstractRepository implements MerchantOpeningHoursStorageRepositoryInterface
{
    /**
     * @param int $fkMerchant
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\WeekdayScheduleTransfer[]
     */
    public function getMerchantOpeningHoursWeekdayScheduleByFkMerchant(int $fkMerchant): ArrayObject
    {
        $weekdayScheduleEntities = $this->getFactory()
            ->getMerchantOpeningHoursWeekdaySchedulePropelQuery()
            ->useSpyWeekdayScheduleQuery()
                ->orderByDay(Criteria::ASC)
                ->orderByTimeFrom(Criteria::ASC)
            ->endUse()
            ->filterByFkMerchant($fkMerchant)
            ->find();

        return $this->mapMerchantOpeningHoursWeekdayScheduleEntitiesToWeekdayScheduleTransfers($weekdayScheduleEntities);
    }

    /**
     * @param int $fkMerchant
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DateScheduleTransfer[]
     */
    public function getMerchantOpeningHoursDateScheduleByFkMerchant(int $fkMerchant): ArrayObject
    {
        $dateScheduleEntities = $this->getFactory()
            ->getMerchantOpeningHoursDateSchedulePropelQuery()
            ->useSpyDateScheduleQuery()
                ->orderByDate(Criteria::ASC)
                ->orderByTimeFrom(Criteria::ASC)
            ->endUse()
            ->filterByFkMerchant($fkMerchant)
            ->find();

        return $this->mapMerchantOpeningHoursDateScheduleEntitiesToDateScheduleTransfers($dateScheduleEntities);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\SpyMerchantOpeningHoursStorageEntityTransfer[]
     */
    public function getFilteredMerchantOpeningHoursStorageEntities(FilterTransfer $filterTransfer, array $merchantIds): array
    {
        $merchantOpeningHoursStoragePropelQuery = $this->getFactory()
            ->getMerchantOpeningHoursStoragePropelQuery();

        if ($merchantIds) {
            $merchantOpeningHoursStoragePropelQuery->filterByFkMerchant_In($merchantIds);
        }

        return $this->buildQueryFromCriteria($merchantOpeningHoursStoragePropelQuery, $filterTransfer)
            ->find();
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer[]
     */
    public function getFilteredMerchantTransfers(FilterTransfer $filterTransfer): array
    {
        $merchantOpeningHoursDateSchedulePropelQuery = $this->getFactory()
            ->getMerchantPropelQuery();

        $merchantEntityTransfers = $this->buildQueryFromCriteria($merchantOpeningHoursDateSchedulePropelQuery, $filterTransfer)
            ->setFormatter(ModelCriteria::FORMAT_OBJECT)
            ->find();

        return $this->mapMerchantEntityTransfersToMerchantTransfers($merchantEntityTransfers->getData());
    }

    /**
     * @param \Orm\Zed\Merchant\Persistence\SpyMerchant[] $merchantEntities
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer[]
     */
    protected function mapMerchantEntityTransfersToMerchantTransfers(array $merchantEntities): array
    {
        $merchantTransfers = [];
        foreach ($merchantEntities as $merchantEntity) {
            $merchantTransfers[] = (new MerchantTransfer())
                ->fromArray($merchantEntity->toArray(), true);
        }

        return $merchantTransfers;
    }

    /**
     * @param \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdaySchedule[]|\Propel\Runtime\Collection\ObjectCollection $weekdayScheduleEntities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\WeekdayScheduleTransfer[]
     */
    protected function mapMerchantOpeningHoursWeekdayScheduleEntitiesToWeekdayScheduleTransfers(ObjectCollection $weekdayScheduleEntities): ArrayObject
    {
        $weekdayScheduleTransfers = new ArrayObject();
        foreach ($weekdayScheduleEntities as $weekdayScheduleEntity) {
            $weekdayScheduleTransfer = $this->mapWeekdayScheduleEntityToWeekdayScheduleTransfer(
                $weekdayScheduleEntity->getSpyWeekdaySchedule()
            );
            $weekdayScheduleTransfers->append($weekdayScheduleTransfer);
        }

        return $weekdayScheduleTransfers;
    }

    /**
     * @param \Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule $weekdayScheduleEntity
     *
     * @return \Generated\Shared\Transfer\WeekdayScheduleTransfer
     */
    protected function mapWeekdayScheduleEntityToWeekdayScheduleTransfer(SpyWeekdaySchedule $weekdayScheduleEntity): WeekdayScheduleTransfer
    {
        return (new WeekdayScheduleTransfer())->fromArray($weekdayScheduleEntity->toArray(), true);
    }

    /**
     * @param \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateSchedule[]|\Propel\Runtime\Collection\ObjectCollection $dateScheduleEntities
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DateScheduleTransfer[]
     */
    protected function mapMerchantOpeningHoursDateScheduleEntitiesToDateScheduleTransfers(ObjectCollection $dateScheduleEntities): ArrayObject
    {
        $dateScheduleTransfers = new ArrayObject();
        foreach ($dateScheduleEntities as $dateScheduleEntity) {
            $dateScheduleTransfer = $this->mapDateScheduleEntityToDateScheduleTransfer(
                $dateScheduleEntity->getSpyDateSchedule()
            );
            $dateScheduleTransfers->append($dateScheduleTransfer);
        }

        return $dateScheduleTransfers;
    }

    /**
     * @param \Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule $dateScheduleEntity
     *
     * @return \Generated\Shared\Transfer\DateScheduleTransfer
     */
    protected function mapDateScheduleEntityToDateScheduleTransfer(SpyDateSchedule $dateScheduleEntity): DateScheduleTransfer
    {
        return (new DateScheduleTransfer())->fromArray($dateScheduleEntity->toArray(), true);
    }
}
