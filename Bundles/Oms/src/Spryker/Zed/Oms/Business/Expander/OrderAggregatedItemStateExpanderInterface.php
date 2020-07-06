<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\Expander;

interface OrderAggregatedItemStateExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function expandOrdersWithAggregatedItemStates(array $orderTransfers): array;
}
