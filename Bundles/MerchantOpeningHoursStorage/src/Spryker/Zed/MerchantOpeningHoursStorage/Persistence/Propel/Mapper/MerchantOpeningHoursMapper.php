<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\DateScheduleTransfer;
use Generated\Shared\Transfer\WeekdayScheduleTransfer;
use Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule;
use Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule;
use Propel\Runtime\Collection\Collection;

class MerchantOpeningHoursMapper implements MerchantOpeningHoursMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdaySchedule> $weekdayScheduleEntities
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WeekdayScheduleTransfer> $weekdayScheduleTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\WeekdayScheduleTransfer>
     */
    public function mapMerchantOpeningHoursWeekdayScheduleEntitiesToWeekdayScheduleTransfers(
        Collection $weekdayScheduleEntities,
        ArrayObject $weekdayScheduleTransfers
    ): ArrayObject {
        foreach ($weekdayScheduleEntities as $weekdayScheduleEntity) {
            $weekdayScheduleTransfer = $this->mapWeekdayScheduleEntityToWeekdayScheduleTransfer(
                $weekdayScheduleEntity->getSpyWeekdaySchedule(),
                new WeekdayScheduleTransfer(),
            );
            $weekdayScheduleTransfers->append($weekdayScheduleTransfer);
        }

        return $weekdayScheduleTransfers;
    }

    /**
     * @param \Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule $weekdayScheduleEntity
     * @param \Generated\Shared\Transfer\WeekdayScheduleTransfer $weekdayScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\WeekdayScheduleTransfer
     */
    protected function mapWeekdayScheduleEntityToWeekdayScheduleTransfer(
        SpyWeekdaySchedule $weekdayScheduleEntity,
        WeekdayScheduleTransfer $weekdayScheduleTransfer
    ): WeekdayScheduleTransfer {
        return $weekdayScheduleTransfer->fromArray($weekdayScheduleEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateSchedule> $dateScheduleEntities
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DateScheduleTransfer> $dateScheduleTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\DateScheduleTransfer>
     */
    public function mapMerchantOpeningHoursDateScheduleEntitiesToDateScheduleTransfers(
        Collection $dateScheduleEntities,
        ArrayObject $dateScheduleTransfers
    ): ArrayObject {
        foreach ($dateScheduleEntities as $dateScheduleEntity) {
            $dateScheduleTransfer = $this->mapDateScheduleEntityToDateScheduleTransfer(
                $dateScheduleEntity->getSpyDateSchedule(),
                new DateScheduleTransfer(),
            );
            $dateScheduleTransfers->append($dateScheduleTransfer);
        }

        return $dateScheduleTransfers;
    }

    /**
     * @param \Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule $dateScheduleEntity
     * @param \Generated\Shared\Transfer\DateScheduleTransfer $dateScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\DateScheduleTransfer
     */
    protected function mapDateScheduleEntityToDateScheduleTransfer(
        SpyDateSchedule $dateScheduleEntity,
        DateScheduleTransfer $dateScheduleTransfer
    ): DateScheduleTransfer {
        return $dateScheduleTransfer->fromArray($dateScheduleEntity->toArray(), true);
    }
}
