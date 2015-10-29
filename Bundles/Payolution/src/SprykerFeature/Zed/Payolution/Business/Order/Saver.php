<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\Payolution\Business\Order;

use Generated\Shared\Payolution\ItemInterface;
use Generated\Shared\Payolution\PayolutionPaymentInterface;
use Generated\Shared\Payolution\OrderInterface;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionOrderItem;

class Saver implements SaverInterface
{

    /**
     * @param OrderInterface $orderTransfer
     */
    public function saveOrderPayment(OrderInterface $orderTransfer)
    {
        $paymentEntity = $this->savePaymentForOrder(
            $orderTransfer->getPayolutionPayment(),
            $orderTransfer->getIdSalesOrder()
        );

        $this->savePaymentForOrderItems(
            $orderTransfer->getItems(),
            $paymentEntity->getIdPaymentPayolution()
        );
    }

    /**
     * @param PayolutionPaymentInterface $paymentTransfer
     * @param int $idSalesOrder
     *
     * @return SpyPaymentPayolution
     */
    private function savePaymentForOrder(PayolutionPaymentInterface $paymentTransfer, $idSalesOrder)
    {
        $paymentEntity = new SpyPaymentPayolution();
        $paymentEntity->fromArray($paymentTransfer->toArray());

        // Take over fields from address transfer
        $addressTransfer = $paymentTransfer->getAddress();
        $paymentEntity->fromArray($addressTransfer->toArray());
        $paymentEntity->setCountryIso2Code($addressTransfer->getIso2Code());

        // Payolution requires a simple string for the street address
        $formattedStreet = trim(sprintf(
            '%s %s %s',
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3()
        ));
        $paymentEntity->setStreet($formattedStreet);

        $paymentEntity->setFkSalesOrder($idSalesOrder);
        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param ItemInterface[] $orderItemTransfers
     * @param int $idPayment
     */
    private function savePaymentForOrderItems($orderItemTransfers, $idPayment)
    {
        /** @var ItemInterface $orderItemTransfer */
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentPayolutionOrderItem();
            $paymentOrderItemEntity
                ->setFkPaymentPayolution($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->save();
        }
    }

}
