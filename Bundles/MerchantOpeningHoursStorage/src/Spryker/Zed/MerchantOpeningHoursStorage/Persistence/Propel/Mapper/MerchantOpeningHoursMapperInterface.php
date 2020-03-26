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
     * @param \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdaySchedule[]|\Propel\Runtime\Collection\ObjectCollection $weekdayScheduleEntities
     * @param \ArrayObject|\Generated\Shared\Transfer\WeekdayScheduleTransfer[] $weekdayScheduleTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\WeekdayScheduleTransfer[]
     */
    public function mapMerchantOpeningHoursWeekdayScheduleEntitiesToWeekdayScheduleTransfers(
        ObjectCollection $weekdayScheduleEntities,
        ArrayObject $weekdayScheduleTransfers
    ): ArrayObject;

    /**
     * @param \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateSchedule[]|\Propel\Runtime\Collection\ObjectCollection $dateScheduleEntities
     * @param \ArrayObject|\Generated\Shared\Transfer\DateScheduleTransfer[] $dateScheduleTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DateScheduleTransfer[]
     */
    public function mapMerchantOpeningHoursDateScheduleEntitiesToDateScheduleTransfers(
        ObjectCollection $dateScheduleEntities,
        ArrayObject $dateScheduleTransfers
    ): ArrayObject;
}
