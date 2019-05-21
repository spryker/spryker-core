<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduleListRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;

interface PriceProductScheduleListFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListRequestTransfer $priceProductScheduleListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function findPriceProductScheduleList(
        PriceProductScheduleListRequestTransfer $priceProductScheduleListRequestTransfer
    ): PriceProductScheduleListResponseTransfer;
}
