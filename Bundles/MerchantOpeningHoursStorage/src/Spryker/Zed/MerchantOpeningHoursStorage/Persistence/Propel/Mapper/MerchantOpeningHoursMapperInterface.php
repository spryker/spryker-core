<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence\Propel\Mapper;

use ArrayObject;
use Propel\Runtime\Collection\ObjectCollection;

interface MerchantOpeningHoursMapperInterface
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdaySchedule> $weekdayScheduleEntities
     * @param \ArrayObject<int, \Generated\Shared\Transfer\WeekdayScheduleTransfer> $weekdayScheduleTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\WeekdayScheduleTransfer>
     */
    public function mapMerchantOpeningHoursWeekdayScheduleEntitiesToWeekdayScheduleTransfers(
        ObjectCollection $weekdayScheduleEntities,
        ArrayObject $weekdayScheduleTransfers
    ): ArrayObject;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateSchedule> $dateScheduleEntities
     * @param \ArrayObject<int, \Generated\Shared\Transfer\DateScheduleTransfer> $dateScheduleTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\DateScheduleTransfer>
     */
    public function mapMerchantOpeningHoursDateScheduleEntitiesToDateScheduleTransfers(
        ObjectCollection $dateScheduleEntities,
        ArrayObject $dateScheduleTransfers
    ): ArrayObject;
}
