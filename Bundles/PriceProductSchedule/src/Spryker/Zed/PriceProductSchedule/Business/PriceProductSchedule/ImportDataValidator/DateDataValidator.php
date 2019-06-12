<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\ImportDataValidator;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;

class DateDataValidator extends AbstractImportDataValidator
{
    protected const ERROR_MESSAGE_ACTIVE_FROM_AND_ACTIVE_TO = 'Dates must be in right format and to date must be greater than from.';

    protected const FORMAT_DATE = 'Y-m-d\TH:i:sO';

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer|null
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): ?PriceProductScheduleListImportErrorTransfer {
        if ($this->isDatesValid($priceProductScheduleImportTransfer) === true) {
            return null;
        }

        return $this->createPriceProductScheduleListImportErrorTransfer(
            $priceProductScheduleImportTransfer,
            static::ERROR_MESSAGE_ACTIVE_FROM_AND_ACTIVE_TO
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isDatesValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        if ($this->isDatesEmpty($priceProductScheduleImportTransfer)) {
            return false;
        }

        $activeFrom = $this->createDateTimeFromFormat($priceProductScheduleImportTransfer->getActiveFrom());

        if ($activeFrom === null) {
            return false;
        }

        $activeTo = $this->createDateTimeFromFormat($priceProductScheduleImportTransfer->getActiveTo());

        if ($activeTo === null) {
            return false;
        }

        return $activeTo > $activeFrom;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isDatesEmpty(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        return $priceProductScheduleImportTransfer->getActiveFrom() === null
            || $priceProductScheduleImportTransfer->getActiveTo() === null;
    }

    /**
     * @param string $date
     *
     * @return \DateTime|null
     */
    protected function createDateTimeFromFormat(string $date): ?DateTime
    {
        $dateTime = DateTime::createFromFormat(static::FORMAT_DATE, $date);

        if ($dateTime === false) {
            return null;
        }

        return $dateTime;
    }
}
