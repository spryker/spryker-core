<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;

interface MerchantCommissionCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateMerchantCommissions(
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer;
}
