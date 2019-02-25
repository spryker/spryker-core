<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Communication\Order;

use Generated\Shared\Transfer\OrderTransfer;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface SalesOrderDataBCForMultiShipmentAdapterInterface
{
    /**
     * @deprecated Exists for Backward Compatibility reasons only.
     *
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function adapt(OrderTransfer $orderTransfer): OrderTransfer;
}
