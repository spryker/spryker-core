<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\OrderItem;

use ArrayObject;

interface ShipmentSalesOrderItemReaderInterface
{
    /**
     * @param int $idSalesOrder
     * @param int $idSalesShipment
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    public function findSalesOrderItemsBySalesShipmentId(int $idSalesOrder, int $idSalesShipment): ArrayObject;
}
