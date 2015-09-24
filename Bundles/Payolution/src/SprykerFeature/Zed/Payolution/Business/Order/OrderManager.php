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
        $this->savePayment($orderTransfer->getPayolutionPayment(), $orderTransfer->getIdSalesOrder());
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

        // Take over fields from address transfer
        $addressTransfer = $paymentTransfer->getAddress();
        $paymentEntity->fromArray($addressTransfer->toArray());
        $paymentEntity->setCountryIso2Code($addressTransfer->getIso2Code());

        // Payolution requires a simple string for the street address
        $formattedStreet = sprintf('%s %s', $addressTransfer->getAddress1(), $addressTransfer->getAddress2());
        $paymentEntity->setStreet($formattedStreet);

        $paymentEntity->setFkSalesOrder($idSalesOrder);
        $paymentEntity->save();

        return $paymentEntity;
    }

}
