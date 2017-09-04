<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard;
use Spryker\Shared\GiftCard\GiftCardConstants;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class SalesOrderPaymentSaver
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveGiftCardPayments(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        if (!$this->hasGiftCardPayments($quoteTransfer)) {
            return;
        }

        $giftCardTransferCollection = $quoteTransfer->getPayments();

        $this->handleDatabaseTransaction(function () use ($giftCardTransferCollection) {
            $this->saveGiftCardPaymentEntities($giftCardTransferCollection);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasGiftCardPayments(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === GiftCardConstants::PROVIDER_NAME) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PaymentTransfer[] $paymentTransferCollection
     *
     * @return void
     */
    protected function saveGiftCardPaymentEntities(ArrayObject $paymentTransferCollection)
    {
        foreach ($paymentTransferCollection as $giftCardTransfer) {
            if (!$giftCardTransfer->getGiftCard()) {
                continue;
            }

            if ($giftCardTransfer->getAmount() <= 0) {
                continue;
            }

            $salesOrderGiftCardEntity = $this->createSalesOrderGiftCardEntityFromTransfer($giftCardTransfer);
            $salesOrderGiftCardEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard
     */
    protected function createSalesOrderGiftCardEntityFromTransfer(PaymentTransfer $paymentTransfer)
    {
        $paymentTransfer->requireIdSalesPayment();
        $paymentTransfer->requireGiftCard();

        $salesOrderGiftCardEntity = new SpyPaymentGiftCard();
        $salesOrderGiftCardEntity->fromArray($paymentTransfer->getGiftCard()->toArray());
        $salesOrderGiftCardEntity->setFkSalesPayment($paymentTransfer->getIdSalesPayment());

        return $salesOrderGiftCardEntity;
    }

}
