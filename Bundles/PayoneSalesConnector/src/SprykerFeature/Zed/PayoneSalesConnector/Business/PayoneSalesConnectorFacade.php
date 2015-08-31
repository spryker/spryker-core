<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneSalesConnector\Business;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PayoneSalesConnectorDependencyContainer getDependencyContainer()
 */
class PayoneSalesConnectorFacade extends AbstractFacade
{

    /**
     * @param ObjectCollection $orders
     * @return array
     */
    public function getLogs(ObjectCollection $orders)
    {
        return $this->getDependencyContainer()->getPayonePaymentLogReceiver()->getPaymentLogs($orders);
    }

}
