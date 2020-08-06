<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderRemunerationTotalExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderTotalsWithRemunerationTotal(OrderTransfer $orderTransfer): OrderTransfer;
}
