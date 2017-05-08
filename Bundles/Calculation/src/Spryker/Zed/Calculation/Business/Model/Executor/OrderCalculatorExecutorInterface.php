<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Executor;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderCalculatorExecutorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return OrderTransfer
     */
    public function recalculate(OrderTransfer $orderTransfer);
}
