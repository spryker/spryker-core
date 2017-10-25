<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\SalesPayment;

use Exception;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface;

class GiftCardSalesPaymentHydrator
{
    /**
     * @var \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface
     */
    protected $giftCardQueryContainer;

    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface $giftCardQueryContainer
     */
    public function __construct(GiftCardQueryContainerInterface $giftCardQueryContainer)
    {
        $this->giftCardQueryContainer = $giftCardQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    public function hydrateGiftCardSalesPayment(OrderTransfer $orderTransfer, PaymentTransfer $paymentTransfer)
    {
        $paymentTransfer->requireIdSalesPayment();
        $giftCardPaymentEntity = $this->getPaymentEntity($paymentTransfer);
        if (!$giftCardPaymentEntity) {
            throw new Exception('No payment information for sales payment id ' . $paymentTransfer->getIdSalesPayment());
        }

        $paymentTransfer->setPaymentMethod('GiftCard ' . $giftCardPaymentEntity->getCode());

        return $paymentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard|null
     */
    protected function getPaymentEntity(PaymentTransfer $paymentTransfer)
    {
        return $this->giftCardQueryContainer->queryPaymentGiftCardsForIdSalesPayment($paymentTransfer->getIdSalesPayment())->findOne();
    }
}
