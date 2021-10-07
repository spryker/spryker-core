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
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard;
use Spryker\Zed\GiftCard\GiftCardConfig;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class SalesOrderPaymentSaver implements SalesOrderPaymentSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var array<\Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface>
     */
    protected $giftCardPaymentSaverPlugins;

    /**
     * @var \Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected $giftCardConfig;

    /**
     * @param array<\Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface> $giftCardPaymentSaverPlugins
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
     * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\Payment\SalesOrderPaymentSaver::saveGiftCardOrderPayments()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveGiftCardPayments(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->executeSaveGiftCardPaymentsTransaction($quoteTransfer, $checkoutResponse);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveGiftCardOrderPayments(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        // For BC reasons only, will not be used in the future.
        $checkoutResponse = new CheckoutResponseTransfer();
        $checkoutResponse->setSaveOrder($saveOrderTransfer);

        $this->executeSaveGiftCardPaymentsTransaction($quoteTransfer, $checkoutResponse);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function executeSaveGiftCardPaymentsTransaction(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse): void
    {
        $giftCardPayments = $this->getGiftCardPayments($quoteTransfer);

        $this->handleDatabaseTransaction(function () use ($giftCardPayments, $checkoutResponse) {
            $this->runGiftCardPaymentSavers($giftCardPayments, $checkoutResponse);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PaymentTransfer>
     */
    protected function getGiftCardPayments(QuoteTransfer $quoteTransfer): ArrayObject
    {
        $result = new ArrayObject();

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === $this->giftCardConfig->getPaymentProviderName()) {
                $result->append($paymentTransfer);
            }
        }

        return $result;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\PaymentTransfer> $paymentTransferCollection
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PaymentTransfer> $paymentTransferCollection
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function runGiftCardPaymentSavers(
        ArrayObject $paymentTransferCollection,
        CheckoutResponseTransfer $checkoutResponse
    ): void {
        foreach ($paymentTransferCollection as $giftCardPayment) {
            if (!$giftCardPayment->getGiftCard()) {
                continue;
            }

            if ($giftCardPayment->getAmount() <= 0) {
                continue;
            }

            $this->saveGiftGardPayment($giftCardPayment);
            $this->runSaverPluginsForPayment($giftCardPayment, $checkoutResponse);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    protected function runSaverPluginsForPayment(PaymentTransfer $paymentTransfer, CheckoutResponseTransfer $checkoutResponse)
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
    protected function saveGiftGardPayment(PaymentTransfer $paymentTransfer)
    {
        $paymentTransfer->requireIdSalesPayment();
        $paymentTransfer->requireGiftCard();

        $paymentGiftCardEntity = new SpyPaymentGiftCard();
        $paymentGiftCardEntity->fromArray($paymentTransfer->getGiftCard()->toArray());
        $paymentGiftCardEntity->setFkSalesPayment($paymentTransfer->getIdSalesPayment());
        $paymentGiftCardEntity->save();

        return $paymentGiftCardEntity;
    }
}
