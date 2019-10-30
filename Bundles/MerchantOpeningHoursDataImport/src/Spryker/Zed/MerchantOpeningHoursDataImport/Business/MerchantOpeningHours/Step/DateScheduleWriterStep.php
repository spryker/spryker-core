<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step;

use Orm\Zed\WeekdaySchedule\Persistence\SpyDateScheduleQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\DataSet\MerchantOpeningHoursDateScheduleDataSetInterface;

class DateScheduleWriterStep implements DataImportStepInterface
{
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantOpeningHoursDateScheduleDataSetInterface::DATE,
        MerchantOpeningHoursDateScheduleDataSetInterface::MERCHANT_OPENING_HOURS_DATE_KEY,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $dateScheduleEntity = SpyDateScheduleQuery::create()
            ->filterByKey($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::MERCHANT_OPENING_HOURS_DATE_KEY])
            ->findOneOrCreate();

        $dateScheduleEntity
            ->setDate($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::DATE])
            ->setTimeFrom($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::TIME_FROM])
            ->setTimeTo($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::TIME_TO])
            ->setNote($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::NOTE]);

        if ($dateScheduleEntity->isNew() || $dateScheduleEntity->isModified()) {
            $dateScheduleEntity->save();
        }

        $dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::FK_DATE_SCHEDULE] = $dateScheduleEntity->getIdDateSchedule();
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
