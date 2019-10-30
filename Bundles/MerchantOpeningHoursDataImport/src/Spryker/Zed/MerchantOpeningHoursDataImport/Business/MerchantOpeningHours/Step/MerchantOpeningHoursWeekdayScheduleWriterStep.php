<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step;

use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\DataSet\MerchantOpeningHoursWeekdayScheduleDataSetInterface;

class MerchantOpeningHoursWeekdayScheduleWriterStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_MERCHANT,
        MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_WEEKDAY_SCHEDULE,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantOpeningHoursWeekdayScheduleEntity = SpyMerchantOpeningHoursWeekdayScheduleQuery::create()
            ->filterByFkMerchant($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_MERCHANT])
            ->filterByFkWeekdaySchedule($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_WEEKDAY_SCHEDULE])
            ->findOneOrCreate();

        $merchantOpeningHoursWeekdayScheduleEntity
            ->setFkMerchant($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_MERCHANT])
            ->setFkWeekdaySchedule($dataSet[MerchantOpeningHoursWeekdayScheduleDataSetInterface::FK_WEEKDAY_SCHEDULE]);

        if ($merchantOpeningHoursWeekdayScheduleEntity->isNew() || $merchantOpeningHoursWeekdayScheduleEntity->isModified()) {
            $merchantOpeningHoursWeekdayScheduleEntity->save();
        }
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
}
