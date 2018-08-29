<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Persistence;

use Generated\Shared\Transfer\OrderListTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface SalesRepositoryInterface
{
    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\OrderListTransfer
     */
    public function getOrderListByCustomerReference(string $customerReference): OrderListTransfer;

    /**
     * @param string $orderReference
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function findOrderByOrderReference(string $orderReference): OrderTransfer;
}
