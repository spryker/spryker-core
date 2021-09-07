<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\Step;

use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery;
use Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantOpeningHoursDataImport\Business\MerchantOpeningHours\DataSet\MerchantOpeningHoursDateScheduleDataSetInterface;

class DateScheduleWriterStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected const REQUIRED_DATA_SET_KEYS = [
        MerchantOpeningHoursDateScheduleDataSetInterface::DATE,
    ];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->validateDataSet($dataSet);

        $merchantOpeningHoursDateScheduleEntity = $this->createMerchantOpeningHoursDateSchedulePropelQuery()
            ->filterByFkMerchant($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::FK_MERCHANT])
            ->useSpyDateScheduleQuery()
                ->filterByDate($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::DATE])
                ->filterByTimeFrom($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::TIME_FROM] ?: null)
            ->endUse()
            ->findOne();

        if ($merchantOpeningHoursDateScheduleEntity !== null) {
            $dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::FK_DATE_SCHEDULE] = $merchantOpeningHoursDateScheduleEntity
                ->getSpyDateSchedule()
                ->getIdDateSchedule();

            return;
        }

        $dateScheduleEntity = $this->createDateScheduleEntity()
            ->setDate($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::DATE])
            ->setTimeFrom($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::TIME_FROM])
            ->setTimeTo($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::TIME_TO])
            ->setNoteGlossaryKey($dataSet[MerchantOpeningHoursDateScheduleDataSetInterface::NOTE_GLOSSARY_KEY]);

        $dateScheduleEntity->save();

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

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery
     */
    protected function createMerchantOpeningHoursDateSchedulePropelQuery(): SpyMerchantOpeningHoursDateScheduleQuery
    {
        return SpyMerchantOpeningHoursDateScheduleQuery::create();
    }

    /**
     * @return \Orm\Zed\WeekdaySchedule\Persistence\SpyDateSchedule
     */
    protected function createDateScheduleEntity(): SpyDateSchedule
    {
        return new SpyDateSchedule();
    }
}
