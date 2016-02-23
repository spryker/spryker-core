<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Dependency\Plugin;

use Propel\Runtime\Collection\ObjectCollection;

interface PaymentLogReceiverInterface
{

    /**
     * TODO FW Do we need this here?
     *
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return array
     */
    public function getPaymentLogs(ObjectCollection $orders);

}
