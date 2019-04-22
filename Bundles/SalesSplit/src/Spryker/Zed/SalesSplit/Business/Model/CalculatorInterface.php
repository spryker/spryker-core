<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit\Business\Model;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface CalculatorInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     * @param float $quantity
     *
     * @return float
     */
    public function calculateQuantityAmountLeft(SpySalesOrderItem $salesOrderItem, $quantity);
}
