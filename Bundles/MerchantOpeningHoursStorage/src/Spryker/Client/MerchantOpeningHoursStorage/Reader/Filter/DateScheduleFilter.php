<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter;

use ArrayObject;
use DateTime;
use Exception;
use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;

class DateScheduleFilter implements DateScheduleFilterInterface
{
    /**
     * Removes all DateSchedule rows with dates in the past
     *
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer
     */
    public function filter(MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer): MerchantOpeningHoursStorageTransfer
    {
        $dateScheduleTransfers = $merchantOpeningHoursStorageTransfer->getDateSchedule();

        $merchantOpeningHoursStorageTransfer->setDateSchedule(new ArrayObject());

        foreach ($dateScheduleTransfers as $dateScheduleTransfer) {
            if ($this->getIsDateCorrectAndInTheFuture($dateScheduleTransfer->getDate())) {
                $merchantOpeningHoursStorageTransfer->addDateSchedule($dateScheduleTransfer);
            }
        }

        return $merchantOpeningHoursStorageTransfer;
    }

    /**
     * @param string|null $date
     *
     * @return bool
     */
    protected function getIsDateCorrectAndInTheFuture(?string $date): bool
    {
        $result = true;

        try {
            $dateTime = new DateTime((string)$date);
            $dateTimeNow = new DateTime();

            if ($dateTimeNow >= $dateTime) {
                $result = false;
            }
        } catch (Exception $exception) {
            $result = false;
        }

        return $result;
    }
}
