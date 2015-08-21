<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Order;

use Generated\Shared\Payone\OrderInterface as PayoneOrderInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Transfer\PayonePaymentDetailsTransfer;
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
        $payment = $this->savePayment($paymentTransfer);

        $paymentDetailsTransfer = $paymentTransfer->getPaymentDetails();
        $this->savePaymentDetails($payment, $paymentDetailsTransfer);
    }

    /**
     * @param PayonePaymentInterface $paymentTransfer
     * @return SpyPaymentPayone
     */
    protected function savePayment(PayonePaymentInterface $paymentTransfer)
    {
        $payment = new SpyPaymentPayone();
        $payment->fromArray(($paymentTransfer->toArray()));
        $payment->save();
        return $payment;
    }

    /**
     * @param SpyPaymentPayone $payment
     * @param PayonePaymentDetailsTransfer $paymentDetailsTransfer
     */
    protected function savePaymentDetails(SpyPaymentPayone $payment, PayonePaymentDetailsTransfer $paymentDetailsTransfer)
    {
        $paymentDetails = new SpyPaymentPayoneDetails();
        $paymentDetails->setSpyPaymentPayone($payment);
        $paymentDetails->fromArray($paymentDetailsTransfer->toArray());
        $paymentDetails->save();
    }


}
