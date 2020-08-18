<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\MerchantOpeningHoursDataImport;

use Codeception\Actor;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery;
use Orm\Zed\WeekdaySchedule\Persistence\SpyDateScheduleQuery;
use Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdayScheduleQuery;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantOpeningHoursDataImportCommunicationTester extends Actor
{
    use _generated\MerchantOpeningHoursDataImportCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantOpeningHoursTablesIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getDateSchedulePropelQuery());
        $this->ensureDatabaseTableIsEmpty($this->getWeekdaySchedulePropelQuery());
        $this->ensureDatabaseTableIsEmpty($this->getMerchantOpeningHoursDateSchedulePropelQuery());
        $this->ensureDatabaseTableIsEmpty($this->getMerchantOpeningHoursWeekdaySchedulePropelQuery());
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyDateScheduleQuery
     */
    public function getDateSchedulePropelQuery(): SpyDateScheduleQuery
    {
        return SpyDateScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdayScheduleQuery
     */
    public function getWeekdaySchedulePropelQuery(): SpyWeekdayScheduleQuery
    {
        return SpyWeekdayScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery
     */
    public function getMerchantOpeningHoursDateSchedulePropelQuery(): SpyMerchantOpeningHoursDateScheduleQuery
    {
        return SpyMerchantOpeningHoursDateScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery
     */
    public function getMerchantOpeningHoursWeekdaySchedulePropelQuery(): SpyMerchantOpeningHoursWeekdayScheduleQuery
    {
        return SpyMerchantOpeningHoursWeekdayScheduleQuery::create();
    }
}
