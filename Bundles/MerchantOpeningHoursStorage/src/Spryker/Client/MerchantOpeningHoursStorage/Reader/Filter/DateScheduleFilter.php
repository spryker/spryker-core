<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;

class DateScheduleFilter implements DateScheduleFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer
     */
    public function filter(MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer): MerchantOpeningHoursStorageTransfer
    {
        $dateScheduleTransfers = $merchantOpeningHoursStorageTransfer->getDateSchedule();

        $merchantOpeningHoursStorageTransfer->setDateSchedule(new ArrayObject());

        foreach ($dateScheduleTransfers as $dateScheduleTransfer) {
            if ($this->isDateInTheFuture($dateScheduleTransfer->getDateOrFail())) {
                $merchantOpeningHoursStorageTransfer->addDateSchedule($dateScheduleTransfer);
            }
        }

        return $merchantOpeningHoursStorageTransfer;
    }

    /**
     * @param string $date
     *
     * @return bool
     */
    protected function isDateInTheFuture(string $date): bool
    {
        $result = true;

        $dateTime = new DateTime($date);
        $dateTimeNow = new DateTime();

        if ($dateTimeNow >= $dateTime) {
            $result = false;
        }

        return $result;
    }
}
