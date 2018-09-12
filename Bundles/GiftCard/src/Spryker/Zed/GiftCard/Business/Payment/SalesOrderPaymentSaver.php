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
use Spryker\Zed\GiftCard\GiftCardConfig;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class SalesOrderPaymentSaver implements SalesOrderPaymentSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface[]
     */
    protected $giftCardPaymentSaverPlugins;

    /** @var \Spryker\Zed\GiftCard\GiftCardConfig */
    protected $giftCardConfig;

    /**
     * @param \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface[] $giftCardPaymentSaverPlugins
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(
        array $giftCardPaymentSaverPlugins,
        GiftCardConfig $giftCardConfig
    ) {
        $this->giftCardPaymentSaverPlugins = $giftCardPaymentSaverPlugins;
        $this->giftCardConfig = $giftCardConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveGiftCardPayments(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $giftCardPayments = $this->getGiftCardPayments($quoteTransfer);

        $this->handleDatabaseTransaction(function () use ($giftCardPayments, $checkoutResponse) {
            $this->runGiftCardPaymentSavers($giftCardPayments, $checkoutResponse);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\PaymentTransfer[]
     */
    protected function getGiftCardPayments(QuoteTransfer $quoteTransfer)
    {
        $result = new ArrayObject();

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === $this->giftCardConfig->getPaymentProviderName()) {
                $result[] = $paymentTransfer;
            }
        }

        return $result;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\PaymentTransfer[] $paymentTransferCollection
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function runGiftCardPaymentSavers(ArrayObject $paymentTransferCollection, CheckoutResponseTransfer $checkoutResponse)
    {
        foreach ($paymentTransferCollection as $giftCardPayment) {
            if (!$giftCardPayment->getGiftCard()) {
                continue;
            }

            if ($giftCardPayment->getAmount() <= 0) {
                continue;
            }

            $salesOrderGiftCardEntity = $this->createSalesOrderGiftCardEntityFromTransfer($giftCardPayment);
            $salesOrderGiftCardEntity->save();

            $this->runSaversForPayment($giftCardPayment, $checkoutResponse);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function runSaversForPayment(PaymentTransfer $paymentTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        foreach ($this->giftCardPaymentSaverPlugins as $giftCardPaymentSaverPlugin) {
            $giftCardPaymentSaverPlugin->savePayment($paymentTransfer, $checkoutResponse);
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
