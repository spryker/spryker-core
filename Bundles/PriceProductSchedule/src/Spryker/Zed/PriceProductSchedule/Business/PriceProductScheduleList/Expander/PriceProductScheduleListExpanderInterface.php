<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\Expander;

use Generated\Shared\Transfer\PriceProductScheduleListTransfer;

interface PriceProductScheduleListExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    public function expandPriceProductScheduleListWithCurrentUser(PriceProductScheduleListTransfer $priceProductScheduleListTransfer): PriceProductScheduleListTransfer;
}
