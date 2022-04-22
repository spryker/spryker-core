<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Persistence;

use Orm\Zed\Sales\Persistence\SpySalesOrderItem;

interface NopaymentQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $orderItem
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Nopayment\Persistence\SpyNopaymentPaid>
     */
    public function queryOrderItem(SpySalesOrderItem $orderItem);
}
