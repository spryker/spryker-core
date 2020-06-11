<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Checker;

interface FlagCheckerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param string $flag
     *
     * @return bool
     */
    public function hasOrderItemsFlag(array $itemTransfers, string $flag): bool;
}
