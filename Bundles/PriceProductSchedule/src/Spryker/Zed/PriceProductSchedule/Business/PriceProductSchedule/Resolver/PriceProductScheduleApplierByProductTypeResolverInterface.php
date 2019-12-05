<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;

interface PriceProductScheduleApplierByProductTypeResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    public function applyPriceProductScheduleByProductType(PriceProductScheduleTransfer $priceProductScheduleTransfer): void;
}
