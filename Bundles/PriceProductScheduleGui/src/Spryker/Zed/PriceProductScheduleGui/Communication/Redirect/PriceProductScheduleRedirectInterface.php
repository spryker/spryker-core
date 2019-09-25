<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Redirect;

use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;

interface PriceProductScheduleRedirectInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer
     */
    public function makeRedirectUrl(PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer): PriceProductScheduleRedirectTransfer;
}
