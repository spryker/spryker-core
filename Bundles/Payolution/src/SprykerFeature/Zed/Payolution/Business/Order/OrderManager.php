<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Order;

use Generated\Shared\Payolution\PayolutionPaymentInterface;
use Generated\Shared\Payolution\OrderInterface;
use SprykerFeature\Zed\Payolution\Persistence\Propel\SpyPaymentPayolution;

class OrderManager implements OrderManagerInterface
{

    /**
     * @param OrderInterface $orderTransfer
     */
    public function saveOrderPayment(OrderInterface $orderTransfer)
    {
        $paymentEntity = $this->savePayment($orderTransfer->getPayolutionPayment());
    }

    /**
     * @param PayolutionPaymentInterface $paymentTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @return SpyPaymentPayolution
     */
    private function savePayment(PayolutionPaymentInterface $paymentTransfer)
    {
        $paymentEntity = new SpyPaymentPayolution();
        $paymentEntity->fromArray($paymentTransfer->toArray());
        $paymentEntity->save();
        return $paymentEntity;
    }

}
