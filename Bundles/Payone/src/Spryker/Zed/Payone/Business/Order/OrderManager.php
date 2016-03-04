<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Order;

use Generated\Shared\Transfer\PaymentDetailTransfer;
use Generated\Shared\Transfer\PayonePaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Propel\Runtime\Propel;
use Spryker\Zed\Payone\PayoneConfig;
use Orm\Zed\Payone\Persistence\SpyPaymentPayone;
use Orm\Zed\Payone\Persistence\SpyPaymentPayoneDetail;
use Propel\Runtime\Propel;
use Spryker\Zed\Payone\PayoneConfig;

class OrderManager implements OrderManagerInterface
{

    /**
     * @var \Spryker\Zed\Payone\PayoneConfig
     */
    private $config;

    /**
     * @param \Spryker\Zed\Payone\PayoneConfig $config
     */
    public function __construct(PayoneConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveOrder(QuoteTransfer $quoteTransfer)
    {
        Propel::getConnection()->beginTransaction();

        $paymentTransfer = $quoteTransfer->getPayonePayment();
        $paymentTransfer->setFkSalesOrder($orderTransfer->getIdSalesOrder());
        $payment = $this->savePayment($paymentTransfer);

        $paymentDetailTransfer = $paymentTransfer->getPaymentDetail();
        $this->savePaymentDetail($payment, $paymentDetailTransfer);

        Propel::getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\PayonePaymentTransfer $paymentTransfer
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
     * @param \Orm\Zed\Payone\Persistence\SpyPaymentPayone $payment
     * @param \Generated\Shared\Transfer\PaymentDetailTransfer $paymentDetailTransfer
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
