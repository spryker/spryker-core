<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DummyPayment\Business;

use Orm\Zed\Sales\Persistence\SpySalesOrder;

interface DummyPaymentFacadeInterface
{

    /**
     * @api
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $salesOrderItems
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $salesOrderEntity
     *
     * @return mixed
     */
    public function refund(array $salesOrderItems, SpySalesOrder $salesOrderEntity);

}
