<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Dependency\Service;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\Customer\CustomerServiceInterface;

class CustomerToCustomerServiceBridge implements CustomerToCustomerServiceInterface
{
    /**
     * @var \Spryker\Service\Customer\CustomerServiceInterface
     */
    private $service;

    /**
     * @param \Spryker\Service\Customer\CustomerServiceInterface $service
     */
    public function __construct(CustomerServiceInterface $service)
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
