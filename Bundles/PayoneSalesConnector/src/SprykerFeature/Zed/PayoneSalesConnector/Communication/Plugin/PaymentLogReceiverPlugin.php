<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneSalesConnector\Communication\Plugin;

use Generated\Shared\Transfer\PayonePaymentLogTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Sales\Dependency\Plugin\PaymentLogReceiverInterface;
use SprykerFeature\Zed\PayoneSalesConnector\Business\PayoneSalesConnectorFacade;

/**
 * @method PayoneSalesConnectorFacade getFacade()
 */
class PaymentLogReceiverPlugin extends AbstractPlugin implements PaymentLogReceiverInterface
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
