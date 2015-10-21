<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payone\Business\Order;

use Generated\Shared\Payone\OrderInterface as PayoneOrderInterface;
use Generated\Shared\Payone\PaymentDetailInterface;
use Generated\Shared\Payone\PayonePaymentInterface;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Payone\PayoneConfig;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayone;
use SprykerFeature\Zed\Payone\Persistence\Propel\SpyPaymentPayoneDetail;

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
     *
     * @return void
     */
    public function saveOrder(PayoneOrderInterface $orderTransfer)
    {
        Propel::getConnection()->beginTransaction();

        $paymentTransfer = $orderTransfer->getPayonePayment();
        $paymentTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $payment = $this->savePayment($paymentTransfer);

        $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();
        $this->savePaymentDetail($payment, $paymentDetailTransfer);

        Propel::getConnection()->commit();
    }

    /**
     * @param PayonePaymentInterface $paymentTransfer
     *
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
     * @param PaymentDetailInterface $paymentDetailTransfer
     *
     * @return void
     */
    protected function savePaymentDetail(SpyPaymentPayone $payment, PaymentDetailInterface $paymentDetailTransfer)
    {
        $paymentDetailEntity = new SpyPaymentPayoneDetail();
        $paymentDetailEntity->setSpyPaymentPayone($payment);
        $paymentDetailEntity->fromArray($paymentDetailTransfer->toArray());
        $paymentDetailEntity->save();
    }

}
