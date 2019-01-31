<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\ShipmentTransfer;
use Propel\Runtime\Collection\ObjectCollection;

interface ShipmentInterface
{
    /**
     * @param int $idShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getShipmentTransferById(int $idShipment): ShipmentTransfer;

    /**
     * @param int $idSalesShipment
     *
     * @return \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Sales\Persistence\SpySalesOrderItem[]
     */
    public function findShipmentItemsByIdSalesShipment(int $idSalesShipment): ObjectCollection;
}
