<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Order;

use Generated\Shared\Payone\OrderInterface as PayoneOrderInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use Generated\Shared\Transfer\PayonePaymentDetailsTransfer;
use SprykerFeature\Zed\Payone\PayoneConfig;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneDetails;

class OrderManager implements OrderManagerInterface
{
    /**
     * @var PayoneConfig
     */
    private $config;

    /**
     * @param PayoneConfig $config
     */
    public function __construct(PayoneConfig $config)
    {
        $this->config = $config;
    }

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

        if ($payment->getReference() === null) {
            $orderEntity = $payment->getSpySalesOrder();
            $payment->setReference($this->config->generatePayoneReference($paymentTransfer, $orderEntity));
        }

        $payment->save();
        return $payment;
    }

    /**
     * @param SpyPaymentPayone $payment
     * @param PayonePaymentDetailsTransfer $paymentDetailsTransfer
     */
    protected function savePaymentDetails(SpyPaymentPayone $payment, PayonePaymentDetailsTransfer $paymentDetailsTransfer)
    {
        $paymentDetailsEntity = new SpyPaymentPayoneDetails();
        $paymentDetailsEntity->setSpyPaymentPayone($payment);
        $paymentDetailsEntity->fromArray($paymentDetailsTransfer->toArray());
        $paymentDetailsEntity->save();
    }

}
