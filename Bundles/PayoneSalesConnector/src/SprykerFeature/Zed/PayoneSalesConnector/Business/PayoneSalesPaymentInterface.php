<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SalesPayoneConnector\Business;

use Generated\Shared\Sales\OrderInterface;
use Generated\Shared\Transfer\CheckoutResponseTransfer;

interface PayoneSalesPaymentInterface
{

    /**
     * @param OrderInterface $order
     */
    public function getPaymentLogs(OrderInterface $order);

}
