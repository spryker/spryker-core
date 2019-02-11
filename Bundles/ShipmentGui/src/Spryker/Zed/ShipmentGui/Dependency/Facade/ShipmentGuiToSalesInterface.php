<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Dependency\Facade;

use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface ShipmentGuiToSalesInterface
{
    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer|null
     */
    public function findOrderByIdSalesOrder(int $idSalesOrder): ?OrderTransfer;

    /**
     * @api
     *
     * @param int $idSalesShipment
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItem[]|\Propel\Runtime\Collection\ObjectCollection
     */
    public function findSalesOrderItemsIdsBySalesShipmentId(int $idSalesShipment): ObjectCollection;
}
