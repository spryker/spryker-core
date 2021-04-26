<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOpeningHoursStorageCriteriaTransfer;

interface MerchantOpeningHoursStorageRepositoryInterface
{
    /**
     * @module MerchantOpeningHours
     *
     * @param int $fkMerchant
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\WeekdayScheduleTransfer[]
     */
    public function getMerchantOpeningHoursWeekdayScheduleByFkMerchant(int $fkMerchant): ArrayObject;

    /**
     * @module MerchantOpeningHours
     *
     * @param int $fkMerchant
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DateScheduleTransfer[]
     */
    public function getMerchantOpeningHoursDateScheduleByFkMerchant(int $fkMerchant): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageCriteriaTransfer $merchantOpeningHoursStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SpyMerchantOpeningHoursStorageEntityTransfer[]
     */
    public function getFilteredMerchantOpeningHoursStorageEntityTransfers(
        MerchantOpeningHoursStorageCriteriaTransfer $merchantOpeningHoursStorageCriteriaTransfer
    ): array;
}
