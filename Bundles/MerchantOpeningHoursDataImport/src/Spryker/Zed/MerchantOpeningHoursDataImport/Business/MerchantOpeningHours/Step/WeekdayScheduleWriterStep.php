<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step;

use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery;
use Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\DataSet\MerchantOpeningHoursWeekdayScheduleDataSetInterface;

class WeekdayScheduleWriterStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantOpeningHoursWeekdayScheduleDataSetInterface::WEEK_DAY_KEY,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantOpeningHoursWeekdayScheduleEntity = $this->createMerchantOpeningHoursWeekdaySchedulePropelQuery()
            ->filterByFkMerchant($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_MERCHANT])
            ->useSpyWeekdayScheduleQuery()
                ->filterByDay($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::WEEK_DAY_KEY])
                ->filterByTimeFrom($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::TIME_FROM] ?: null)
            ->endUse()
            ->findOne();

        if ($merchantOpeningHoursWeekdayScheduleEntity !== null) {
            $dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_WEEKDAY_SCHEDULE] = $merchantOpeningHoursWeekdayScheduleEntity
                ->getSpyWeekdaySchedule()
                ->getIdWeekdaySchedule();

            return;
        }

        $weekdayScheduleEntity = $this->createWeekdayScheduleEntity()
            ->setDay($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::WEEK_DAY_KEY])
            ->setTimeFrom($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::TIME_FROM])
            ->setTimeTo($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::TIME_TO]);

        $weekdayScheduleEntity->save();

        $dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_WEEKDAY_SCHEDULE] = $weekdayScheduleEntity->getIdWeekdaySchedule();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function validateDataSet(DataSetInterface $dataSet): void
    {
        foreach (static::REQUIRED_DATA_SET_KEYS as $requiredDataSetKey) {
            $this->validateRequireDataSetByKey($dataSet, $requiredDataSetKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $requiredDataSetKey
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function validateRequireDataSetByKey(DataSetInterface $dataSet, string $requiredDataSetKey): void
    {
        if (!$dataSet[$requiredDataSetKey]) {
            throw new InvalidDataException(sprintf('"%s" is required.', $requiredDataSetKey));
        }
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery
     */
    protected function createMerchantOpeningHoursWeekdaySchedulePropelQuery(): SpyMerchantOpeningHoursWeekdayScheduleQuery
    {
        return SpyMerchantOpeningHoursWeekdayScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyWeekdaySchedule
     */
    protected function createWeekdayScheduleEntity(): SpyWeekdaySchedule
    {
        return new SpyWeekdaySchedule();
    }
}
