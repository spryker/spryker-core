<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Dependency\Service;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Tax\TaxServiceInterface;

class TaxProductConnectorToTaxServiceBridge implements TaxProductConnectorToTaxServiceInterface
{
    /**
     * @var \Spryker\Service\Tax\TaxServiceInterface
     */
    private $service;

    /**
     * @param \Spryker\Service\Tax\TaxServiceInterface $service
     */
    public function __construct(TaxServiceInterface $service)
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
