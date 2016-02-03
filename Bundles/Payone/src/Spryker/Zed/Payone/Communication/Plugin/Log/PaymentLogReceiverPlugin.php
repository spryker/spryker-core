<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Log;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Payone\Business\PayoneFacade getFacade()
 * @method \Spryker\Zed\Payone\Communication\PayoneCommunicationFactory getFactory()
 */
class PaymentLogReceiverPlugin extends AbstractPlugin implements PaymentLogReceiverPluginInterface
{

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return \Generated\Shared\Transfer\PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        return $this->getFacade()->getPaymentLogs($orders);
    }

}
