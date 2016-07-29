<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Dependency\Facade;

use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface PayoneToRefundInterface
{

    /**
     * @param array $orderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Generated\Shared\Transfer\RefundTransfer
     */
    public function calculateRefund(array $orderItems, SpySalesOrder $orderEntity);

}
