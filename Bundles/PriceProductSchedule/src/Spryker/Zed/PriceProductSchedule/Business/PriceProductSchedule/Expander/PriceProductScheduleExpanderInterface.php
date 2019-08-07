<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Expander;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function expandPriceProductScheduleTransferWithPriceProductScheduleList(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleTransfer;
}
