<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CustomerCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\OrderTransfer;

interface CustomerOrderHydratorInterface
{

    /**
     * @param OrderTransfer $orderTransfer
     * @param CheckoutRequestTransfer $request
     */
    public function hydrateOrderTransfer(OrderTransfer $orderTransfer, CheckoutRequestTransfer $request);

}
