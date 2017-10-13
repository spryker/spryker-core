<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Order;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PayolutionPaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolution;
use Orm\Zed\Payolution\Persistence\SpyPaymentPayolutionOrderItem;

class Saver implements SaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    public function saveOrderPayment(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer)
    {
        $paymentEntity = $this->savePaymentForOrder(
            $quoteTransfer->getPayment()->getPayolution(),
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        $this->savePaymentForOrderItems(
            $checkoutResponseTransfer->getSaveOrder()->getOrderItems(),
            $paymentEntity->getIdPaymentPayolution()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PayolutionPaymentTransfer $paymentTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Payolution\Persistence\SpyPaymentPayolution
     */
    protected function savePaymentForOrder(PayolutionPaymentTransfer $paymentTransfer, $idSalesOrder)
    {
        $paymentEntity = new SpyPaymentPayolution();
        $addressTransfer = $paymentTransfer->getAddress();

        $formattedStreet = trim(sprintf(
            '%s %s %s',
            $addressTransfer->getAddress1(),
            $addressTransfer->getAddress2(),
            $addressTransfer->getAddress3()
        ));

        $paymentEntity->fromArray($addressTransfer->toArray());
        $paymentEntity->fromArray($paymentTransfer->toArray());

        $paymentEntity
            ->setStreet($formattedStreet)
            ->setCountryIso2Code($addressTransfer->getIso2Code())
            ->setFkSalesOrder($idSalesOrder);

        $paymentEntity->save();

        return $paymentEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $orderItemTransfers
     * @param int $idPayment
     *
     * @return void
     */
    protected function savePaymentForOrderItems($orderItemTransfers, $idPayment)
    {
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $paymentOrderItemEntity = new SpyPaymentPayolutionOrderItem();
            $paymentOrderItemEntity
                ->setFkPaymentPayolution($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->save();
        }
    }
}
