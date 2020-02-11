<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;

interface PriceProductScheduleListFinderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $requestedPriceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function findPriceProductScheduleList(
        PriceProductScheduleListTransfer $requestedPriceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer;

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findDefaultPriceProductScheduleList(): ?PriceProductScheduleListTransfer;
}
