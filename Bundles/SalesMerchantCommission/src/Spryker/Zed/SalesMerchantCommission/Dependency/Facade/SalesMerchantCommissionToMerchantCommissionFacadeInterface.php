<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Dependency\Facade;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer;

interface SalesMerchantCommissionToMerchantCommissionFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCalculationResponseTransfer
     */
    public function calculateMerchantCommission(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): MerchantCommissionCalculationResponseTransfer;
}
