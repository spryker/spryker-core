<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Checker;

use Generated\Shared\Transfer\OrderTransfer;

interface FlagCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param string $flag
     *
     * @return bool
     */
    public function hasOrderItemsFlag(OrderTransfer $orderTransfer, string $flag): bool;
}
