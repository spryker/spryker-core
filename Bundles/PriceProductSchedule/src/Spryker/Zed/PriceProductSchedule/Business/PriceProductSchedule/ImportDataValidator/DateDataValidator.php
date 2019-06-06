<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\ImportDataValidator;

use DateTime;
use Exception;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;

class DateDataValidator extends AbstractImportDataValidator
{
    protected const ERROR_MESSAGE_ACTIVE_FROM_AND_ACTIVE_TO = 'Dates must be in right format and to date must be greater than from.';

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
        if ($priceProductScheduleImportTransfer->getActiveFrom() === null
            || $priceProductScheduleImportTransfer->getActiveTo() === null) {
            return false;
        }

        try {
            $activeFrom = $this->convertDateStringToDateTime($priceProductScheduleImportTransfer->getActiveFrom());
            $activeTo = $this->convertDateStringToDateTime($priceProductScheduleImportTransfer->getActiveTo());

            return $activeTo > $activeFrom;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @param string $date
     *
     * @return \DateTime
     */
    protected function convertDateStringToDateTime(string $date): DateTime
    {
        return new DateTime($date);
    }
}
