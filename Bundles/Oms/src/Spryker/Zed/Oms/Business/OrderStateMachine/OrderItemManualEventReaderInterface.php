<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Business\OrderStateMachine;

use ArrayObject;

interface OrderItemManualEventReaderInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     *
     * @return string[][]
     */
    public function getManualEventsByIdSalesOrder(ArrayObject $orderItemTransfers): array;
}
