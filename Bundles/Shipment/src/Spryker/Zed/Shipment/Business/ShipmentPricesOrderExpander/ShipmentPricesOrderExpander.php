<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentPricesOrderExpander;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Shipment\Persistence\ShipmentRepositoryInterface;

class ShipmentPricesOrderExpander implements ShipmentPricesOrderExpanderInterface
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderWithShipmentPrices(OrderTransfer $orderTransfer): OrderTransfer
    {
        foreach ($orderTransfer->getShipmentMethods() as $shipmentMethodTransfer) {
            $shipmentMethodPriceTransfers = $this->shipmentRepository
                ->getShipmentMethodPricesByIdShipmentMethod($shipmentMethodTransfer->getIdShipmentMethod());
            $shipmentMethodTransfer->setPrices($shipmentMethodPriceTransfers);
        }

        return $orderTransfer;
    }
}
