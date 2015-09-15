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
        $paymentEntity = $this->savePayment($orderTransfer->getPayolutionPayment(), $orderTransfer->getIdSalesOrder());
    }

    /**
     * @param PayolutionPaymentInterface $paymentTransfer
     * @param $idSalesOrder
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return SpyPaymentPayolution
     */
    private function savePayment(PayolutionPaymentInterface $paymentTransfer, $idSalesOrder)
    {
        $paymentEntity = new SpyPaymentPayolution();
        $paymentEntity->fromArray($paymentTransfer->toArray());
        $paymentEntity->setFkSalesOrder($idSalesOrder);
        $paymentEntity->save();

        return $paymentEntity;
    }

}
