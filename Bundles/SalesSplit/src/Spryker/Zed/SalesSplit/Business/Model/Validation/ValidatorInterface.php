<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesSplit\Business\Model\Validation;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface ValidatorInterface
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $salesOrderItem
     * @param float $quantityToSplit
     *
     * @return bool
     */
    public function isValid(SpySalesOrderItem $salesOrderItem, $quantityToSplit);

    /**
     * @return array
     */
    public function getMessages();
}
