<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOpeningHoursStorage;

use Codeception\Actor;
use Generated\Shared\DataBuilder\DateScheduleBuilder;
use Generated\Shared\DataBuilder\WeekdayScheduleBuilder;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateSchedule;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdaySchedule;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery;
use Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorage;
use Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorageQuery;
use Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule;
use Orm\Zed\WeekdaySchedule\Persistence\SpyDateScheduleQuery;
use Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule;
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
class MerchantOpeningHoursStorageCommunicationTester extends Actor
{
    use _generated\MerchantOpeningHoursStorageCommunicationTesterActions;

    /**
     * @return void
     */
    public function ensureMerchantOpeningHoursTablesIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty($this->getDateSchedulePropelQuery());
        $this->ensureDatabaseTableIsEmpty($this->getWeekdaySchedulePropelQuery());
        $this->ensureDatabaseTableIsEmpty($this->getMerchantOpeningHoursDateSchedulePropelQuery());
        $this->ensureDatabaseTableIsEmpty($this->getMerchantOpeningHoursWeekdaySchedulePropelQuery());
        $this->ensureDatabaseTableIsEmpty($this->getMerchantOpeningHoursStoragePropelQuery());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateSchedule
     */
    public function createMerchantOpeningHoursDateSchedule(MerchantTransfer $merchantTransfer): SpyMerchantOpeningHoursDateSchedule
    {
        $dateScheduleEntity = $this->createDateSchedule();

        $merchantOpeningHoursDateScheduleEntity = (new SpyMerchantOpeningHoursDateSchedule())
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setFkDateSchedule($dateScheduleEntity->getIdDateSchedule());

        $merchantOpeningHoursDateScheduleEntity->save();

        return $merchantOpeningHoursDateScheduleEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdaySchedule
     */
    public function createMerchantOpeningHoursWeekdaySchedule(MerchantTransfer $merchantTransfer): SpyMerchantOpeningHoursWeekdaySchedule
    {
        $weekdayScheduleEntity = $this->createWeekdaySchedule();

        $merchantOpeningHoursWeekdayScheduleEntity = (new SpyMerchantOpeningHoursWeekdaySchedule())
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setFkWeekdaySchedule($weekdayScheduleEntity->getIdWeekdaySchedule());

        $merchantOpeningHoursWeekdayScheduleEntity->save();

        return $merchantOpeningHoursWeekdayScheduleEntity;
    }

    /**
     * @param int $fkMerchant
     *
     * @return \Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorage|null
     */
    public function findMerchantOpeningHoursByFkMerchant(int $fkMerchant): ?SpyMerchantOpeningHoursStorage
    {
        return $this->getMerchantOpeningHoursStoragePropelQuery()
            ->filterByFkMerchant($fkMerchant)
            ->findOne();
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule
     */
    protected function createDateSchedule(): SpyDateSchedule
    {
        $dateScheduleTransfer = (new DateScheduleBuilder())->build();
        $dateScheduleEntity = (new SpyDateSchedule())
            ->setDate($dateScheduleTransfer->getDate())
            ->setTimeFrom($dateScheduleTransfer->getTimeFrom())
            ->setTimeTo($dateScheduleTransfer->getTimeTo())
            ->setNoteGlossaryKey($dateScheduleTransfer->getNoteGlossaryKey());

        $dateScheduleEntity->save();

        return $dateScheduleEntity;
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule
     */
    protected function createWeekdaySchedule(): SpyWeekdaySchedule
    {
        $weekdaySchedule = (new WeekdayScheduleBuilder())->build();
        $weekdayScheduleEntity = (new SpyWeekdaySchedule())
            ->setDay($weekdaySchedule->getDay())
            ->setTimeFrom($weekdaySchedule->getTimeFrom())
            ->setTimeTo($weekdaySchedule->getTimeTo());

        $weekdayScheduleEntity->save();

        return $weekdayScheduleEntity;
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyDateScheduleQuery
     */
    protected function getDateSchedulePropelQuery(): SpyDateScheduleQuery
    {
        return SpyDateScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdayScheduleQuery
     */
    protected function getWeekdaySchedulePropelQuery(): SpyWeekdayScheduleQuery
    {
        return SpyWeekdayScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery
     */
    protected function getMerchantOpeningHoursDateSchedulePropelQuery(): SpyMerchantOpeningHoursDateScheduleQuery
    {
        return SpyMerchantOpeningHoursDateScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery
     */
    protected function getMerchantOpeningHoursWeekdaySchedulePropelQuery(): SpyMerchantOpeningHoursWeekdayScheduleQuery
    {
        return SpyMerchantOpeningHoursWeekdayScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorageQuery
     */
    protected function getMerchantOpeningHoursStoragePropelQuery(): SpyMerchantOpeningHoursStorageQuery
    {
        return SpyMerchantOpeningHoursStorageQuery::create();
    }
}
