<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Order;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionOrderItem;

class Saver implements SaverInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function saveOrderPayment(OrderTransfer $orderTransfer)
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
     * @param \Generated\Shared\Transfer\PayolutionPaymentTransfer $paymentTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    private function savePaymentForOrder(PayolutionPaymentTransfer $paymentTransfer, $idSalesOrder)
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     * @param int $idPayment
     *
     * @return void
     */
    private function savePaymentForOrderItems($orderItemTransfers, $idPayment)
    {
        /** @var \Generated\Shared\Transfer\ItemTransfer $orderItemTransfer */
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentPayolutionOrderItem();
            $paymentOrderItemEntity
                ->setFkPaymentPayolution($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->save();
        }
    }

}
