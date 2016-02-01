<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Communication\Plugin\Log;

use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Payone\Business\PayoneFacade;
use Spryker\Zed\Payone\Communication\PayoneCommunicationFactory;

/**
 * @method PayoneFacade getFacade()
 * @method PayoneCommunicationFactory getFactory()
 */
class PaymentLogReceiverPlugin extends AbstractPlugin implements PaymentLogReceiverPluginInterface
{

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $orders
     *
     * @return PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        return $this->getFacade()->getPaymentLogs($orders);
    }

}
