<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency\Service;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Sales\SalesServiceInterface;

class ShipmentToSalesServiceBridge implements ShipmentToSalesServiceInterface
{
    /**
     * @var \Spryker\Service\Sales\SalesServiceInterface
     */
    private $service;

    /**
     * @param \Spryker\Service\Sales\SalesServiceInterface $service
     */
    public function __construct(SalesServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkQuoteItemHasOwnShipmentTransfer(QuoteTransfer $quoteTransfer): bool
    {
        return $this->service->checkQuoteItemHasOwnShipmentTransfer($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkOrderItemHasOwnShipmentTransfer(OrderTransfer $orderTransfer): bool
    {
        return $this->service->checkOrderItemHasOwnShipmentTransfer($orderTransfer);
    }
}
