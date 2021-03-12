<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;

interface DateScheduleFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer
     */
    public function filter(MerchantOpeningHoursStorageTransfer $merchantOpeningHoursStorageTransfer): MerchantOpeningHoursStorageTransfer;
}
