<?php

declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter;

use DateTime;
use Exception;
use Generated\Shared\Transfer\DateScheduleTransfer;

class MerchantOpeningHoursStorageReaderDateScheduleFilter implements MerchantOpeningHoursStorageReaderFilterInterface
{
    /**
     * Removes all DateSchedule rows with dates in the past
     *
     * @inheritDoc
     *
     * @api
     */
    public function filter(array $merchantOpeningHoursStorageData): array
    {
        $filteredScheduleData = [];

        if (isset($merchantOpeningHoursStorageData['date_schedule'])) {
            foreach ($merchantOpeningHoursStorageData['date_schedule'] as $scheduleData) {
                $date = $scheduleData[DateScheduleTransfer::DATE] ?? null;

                if ($date !== null && $this->dateIsCorrectAndInTheFuture((string)$date)) {
                    $filteredScheduleData[] = $scheduleData;
                }
            }
            $merchantOpeningHoursStorageData['date_schedule'] = $filteredScheduleData;
        }

        return $merchantOpeningHoursStorageData;
    }

    /**
     * @param string $date
     *
     * @return bool
     */
    protected function dateIsCorrectAndInTheFuture(string $date): bool
    {
        $result = true;

        try {
            $dateTime = new DateTime($date);
            $now = new DateTime();

            if ($now >= $dateTime) {
                $result = false;
            }
        } catch (Exception $exception) {
            $result = false;
        }

        return $result;
    }
}
