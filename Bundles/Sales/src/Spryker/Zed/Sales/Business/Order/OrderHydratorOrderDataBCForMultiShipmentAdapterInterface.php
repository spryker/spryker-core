<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Order;

use Orm\Zed\Sales\Persistence\SpySalesOrder;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface OrderHydratorOrderDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrder $orderEntity
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrder
     */
    public function adapt(SpySalesOrder $orderEntity): SpySalesOrder;
}
