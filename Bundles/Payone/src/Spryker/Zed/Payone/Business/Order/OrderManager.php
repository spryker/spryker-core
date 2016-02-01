<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\Payone\PayoneConfig;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;

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
     * @param PayonePaymentTransfer $paymentTransfer
     *
     * @return \Orm\Zed\Payone\Persistence\SpyPaymentPayone
     */
    protected function savePayment(PayonePaymentTransfer $paymentTransfer)
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
     * @param PaymentDetailTransfer $paymentDetailTransfer
     *
     * @return void
     */
    protected function savePaymentDetail(SpyPaymentPayone $payment, PaymentDetailTransfer $paymentDetailTransfer)
    {
        $paymentDetailEntity = new SpyPaymentPayoneDetail();
        $paymentDetailEntity->setSpyPaymentPayone($payment);
        $paymentDetailEntity->fromArray($paymentDetailTransfer->toArray());
        $paymentDetailEntity->save();
    }

}
