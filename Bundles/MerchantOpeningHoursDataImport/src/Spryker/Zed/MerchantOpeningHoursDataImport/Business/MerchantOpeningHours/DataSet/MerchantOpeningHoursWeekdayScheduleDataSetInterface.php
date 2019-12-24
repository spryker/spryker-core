<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\DataSet;

interface MerchantOpeningHoursWeekdayScheduleDataSetInterface extends MerchantOpeningHoursDataSetInterface
{
    public const FK_WEEKDAY_SCHEDULE = 'fk_weekday_schedule';
    public const WEEK_DAY_KEY = 'week_day_key';
    public const TIME_FROM = 'time_from';
    public const TIME_TO = 'time_to';
}
