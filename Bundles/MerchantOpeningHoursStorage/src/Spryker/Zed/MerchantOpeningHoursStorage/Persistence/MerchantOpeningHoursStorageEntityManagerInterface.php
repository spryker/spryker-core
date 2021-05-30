<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence;

use Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer;

interface MerchantOpeningHoursStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer $merchantOpenHoursStorageTransfer
     * @param int $fkMerchant
     *
     * @return void
     */
    public function saveMerchantOpenHoursStorage(MerchantOpeningHoursStorageTransfer $merchantOpenHoursStorageTransfer, int $fkMerchant): void;
}
