<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Nopayment\Persistence;

use Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem;

interface NopaymentQueryContainerInterface
{

    /**
     * @param \Orm\Zed\Sales\Persistence\Base\SpySalesOrderItem $orderItem
     *
     * @return \Orm\Zed\Nopayment\Persistence\SpyNopaymentPaidQuery
     */
    public function queryOrderItem(SpySalesOrderItem $orderItem);

}
