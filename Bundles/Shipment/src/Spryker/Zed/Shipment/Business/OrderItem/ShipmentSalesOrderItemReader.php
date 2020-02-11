<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\OrderItem;

use ArrayObject;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentSalesOrderItemReader implements ShipmentSalesOrderItemReaderInterface
{
    /**
     * @var \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface
     */
    protected $shipmentRepository;

    /**
     * @param \Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface $shipmentRepository
     */
    public function __construct(ShipmentRepositoryInterface $shipmentRepository)
    {
        $this->shipmentRepository = $shipmentRepository;
    }

    /**
     * @param int $idSalesOrder
     * @param int $idSalesShipment
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]|\ArrayObject
     */
    public function findSalesOrderItemsBySalesShipmentId(int $idSalesOrder, int $idSalesShipment): ArrayObject
    {
        return $this->shipmentRepository->findSalesOrderItemsBySalesShipmentId($idSalesOrder, $idSalesShipment);
    }
}
