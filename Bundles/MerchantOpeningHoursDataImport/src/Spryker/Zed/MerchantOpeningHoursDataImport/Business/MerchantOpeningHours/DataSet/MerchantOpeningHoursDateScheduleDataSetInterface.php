<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\DataSet;

interface MerchantOpeningHoursDateScheduleDataSetInterface extends MerchantOpeningHoursDataSetInterface
{
    public const FK_DATE_SCHEDULE = 'fk_date_schedule';
    public const DATE = 'date';
    public const TIME_FROM = 'time_from';
    public const TIME_TO = 'time_to';
    public const NOTE_GLOSSARY_KEY = 'note_glossary_key';
}
