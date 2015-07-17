<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Order;

use Generated\Shared\Payone\OrderInterface as PayoneOrderInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneDetails;

class OrderManager implements OrderManagerInterface
{

    /**
     * @param PayoneOrderInterface $orderTransfer
     */
    public function saveOrder(PayoneOrderInterface $orderTransfer)
    {
        $paymentTransfer = $orderTransfer->getPayonePayment();
        $this->persistPayment($paymentTransfer);
    }

    /**
     * @param PayonePaymentInterface $paymentTransfer
     */
    protected function persistPayment(PayonePaymentInterface $paymentTransfer)
    {
        $payment = new SpyPaymentPayone();
        $payment->setPaymentMethod($paymentTransfer->getPaymentMethod());
        $payment->setAuthorizationType($paymentTransfer->getAuthorizationType());
        $payment->setTransactionId($paymentTransfer->getTransactionId());
        $payment->save();

        $paymentDetailsTransfer = $paymentTransfer->getPaymentDetails();
        $paymentDetails = new SpyPaymentPayoneDetails();
        $paymentDetails->setSpyPaymentPayone($payment);
        $paymentDetails->setPseudocardpan($paymentDetailsTransfer->getPseudocardpan());
        $paymentDetails->save();
    }

}
