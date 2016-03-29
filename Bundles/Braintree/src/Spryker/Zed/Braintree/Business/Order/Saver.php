<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Order;

use Generated\Shared\Transfer\BraintreePaymentTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintree;
use Orm\Zed\Braintree\Persistence\SpyPaymentBraintreeOrderItem;

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
            $quoteTransfer->getPayment()->getBraintree(),
            $checkoutResponseTransfer->getSaveOrder()->getIdSalesOrder()
        );

        $this->savePaymentForOrderItems(
            $checkoutResponseTransfer->getSaveOrder()->getOrderItems(),
            $paymentEntity->getIdPaymentBraintree()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\BraintreePaymentTransfer $paymentTransfer
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\Braintree\Persistence\SpyPaymentBraintree
     */
    protected function savePaymentForOrder(BraintreePaymentTransfer $paymentTransfer, $idSalesOrder)
    {
        $paymentEntity = new SpyPaymentBraintree();

        $paymentEntity->fromArray($paymentTransfer->toArray());

        $paymentEntity
            ->setFkSalesOrder($idSalesOrder);

        //FIXME: needed?
        $paymentEntity->setClientIp('123');
        $paymentEntity->setFirstName('123');
        $paymentEntity->setLastName('123');
        $paymentEntity->setSalutation('Mr');
        $paymentEntity->setGender('Male');
        $paymentEntity->setCountryIso2Code('DE');
        $paymentEntity->setCity('DE');
        $paymentEntity->setZipCode('12345');
        $paymentEntity->setStreet('DE');
        $paymentEntity->setEmail('email@email.de');
        $paymentEntity->setLanguageIso2Code('DE');
        $paymentEntity->setCurrencyIso3Code('EUR');

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
            $paymentOrderItemEntity = new SpyPaymentBraintreeOrderItem();
            $paymentOrderItemEntity
                ->setFkPaymentBraintree($idPayment)
                ->setFkSalesOrderItem($orderItemTransfer->getIdSalesOrderItem());
            $paymentOrderItemEntity->save();
        }
    }

}
