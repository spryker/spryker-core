<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms\Persistence;

use Generated\Shared\Transfer\SalesOrderItemTransfer;

interface SalesOmsRepositoryInterface
{
    /**
     * @param string $orderItemReference
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemTransfer|null
     */
    public function findSalesOrderItemByOrderItemReference(string $orderItemReference): ?SalesOrderItemTransfer;
}
