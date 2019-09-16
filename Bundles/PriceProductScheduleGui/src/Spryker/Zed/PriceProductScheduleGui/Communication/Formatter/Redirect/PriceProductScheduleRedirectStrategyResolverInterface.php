<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Formatter\Redirect;

use Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer;

interface PriceProductScheduleRedirectStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleRedirectTransfer
     */
    public function resolvePriceProductScheduleRedirectStrategy(PriceProductScheduleRedirectTransfer $priceProductScheduleRedirectTransfer): PriceProductScheduleRedirectTransfer;
}
