<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneSalesConnector\Dependency\Facade;

use Generated\Shared\Sales\OrderInterface;
use Propel\Runtime\Collection\ObjectCollection;

interface PayoneSalesConnectorToPayoneInterface
{

    /**
     * @param ObjectCollection $orders
     * @return array
     */
    public function getPaymentLogs(ObjectCollection $orders);

}
