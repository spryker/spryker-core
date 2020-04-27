<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Calculator;

use Generated\Shared\Transfer\ReturnTotalsTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

interface ReturnTotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTotalsTransfer
     */
    public function calculateReturnTotals(ReturnTransfer $returnTransfer): ReturnTotalsTransfer;
}
