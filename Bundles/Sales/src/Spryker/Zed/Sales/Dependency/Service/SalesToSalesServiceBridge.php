<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Dependency\Service;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Sales\Shipment\SalesServiceInterface;

class SalesToSalesServiceBridge implements SalesToSalesServiceInterface
{
    /**
     * @var \Spryker\Sales\Shipment\SalesServiceInterface
     */
    private $salesService;

    /**
     * @param \Spryker\Sales\Shipment\SalesServiceInterface $salesService
     */
    public function __construct(SalesServiceInterface $salesService)
    {
        $this->salesService = $salesService;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function checkSplitDeliveryEnabledByQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $this->salesService->checkSplitDeliveryEnabledByQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function checkSplitDeliveryEnabledByOrder(OrderTransfer $orderTransfer): bool
    {
        return $this->salesService->checkSplitDeliveryEnabledByOrder($orderTransfer);
    }
}
