<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Communication\Plugin;

use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Payone\Business\PayoneFacade;

/**
 * @method PayoneFacade getFacade()
 */
class PaymentLogReceiverPlugin extends AbstractPlugin implements PaymentLogReceiverPluginInterface
{

    /**
     * @param ObjectCollection $orders
     *
     * @return PayonePaymentLogTransfer[]
     */
    public function getPaymentLogs(ObjectCollection $orders)
    {
        return $this->getFacade()->getPaymentLogs($orders);
    }

}
