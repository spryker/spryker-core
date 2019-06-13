<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use DateTime;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class DateValidatorStep implements DataImportStepInterface
{
    protected const DATE_EMPTY_EXCEPTION_MESSAGE = 'Both dates should not be empty';
    protected const END_DATE_SHOULD_BE_GREATER_THAN_START_DATE_EXCEPTION_MESSAGE = 'End date should be greater than start date';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->hasDataSetDates($dataSet)) {
            throw new EntityNotFoundException(static::DATE_EMPTY_EXCEPTION_MESSAGE);
        }

        if (!$this->isEndDateGreaterThanStartDate($dataSet)) {
            throw new InvalidDataException(static::END_DATE_SHOULD_BE_GREATER_THAN_START_DATE_EXCEPTION_MESSAGE);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function hasDataSetDates(DataSetInterface $dataSet): bool
    {
        return !empty($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_FROM]) &&
            !empty($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_TO]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isEndDateGreaterThanStartDate(DataSetInterface $dataSet): bool
    {
        $startDate = new DateTime($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_FROM]);
        $endDate = new DateTime($dataSet[PriceProductScheduleDataSetInterface::KEY_INCLUDED_TO]);

        return $endDate > $startDate;
    }
}
