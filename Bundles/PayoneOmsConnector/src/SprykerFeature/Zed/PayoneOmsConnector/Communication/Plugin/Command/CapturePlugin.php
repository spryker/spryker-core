<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PayoneOmsConnector\Communication\Plugin\Command;

use Generated\Shared\Transfer\CaptureTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Oms\Business\Util\ReadOnlyArrayObject;
use SprykerFeature\Zed\Oms\Communication\Plugin\Oms\Command\CommandByOrderInterface;
use SprykerFeature\Zed\Payone\Business\PayoneDependencyContainer;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrder;

/**
 * @method PayoneDependencyContainer getDependencyContainer()
 */
class CapturePlugin extends AbstractPlugin implements CommandByOrderInterface
{

    /**
     * @param array $orderItems
     * @param SpySalesOrder $orderEntity
     * @param ReadOnlyArrayObject $data
     */
    public function run(array $orderItems, SpySalesOrder $orderEntity, ReadOnlyArrayObject $data)
    {
        $captureTransfer = new CaptureTransfer();
        $captureTransfer->setAmount($orderEntity->getGrandTotal());

        $payment = $orderEntity->getPayonePayment();

        $paymentTransfer = new PayonePaymentTransfer();
        $paymentTransfer->setPaymentMethod($payment->getMethod());
        $paymentTransfer->setTransactionId($payment->getTransactionId());

        $this->getDependencyContainer()->createPayoneFacade()->capture($captureTransfer);
    }

}
