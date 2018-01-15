<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\GiftCard\GiftCardConfig as SharedGiftCardConfig;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface;
use Spryker\Zed\GiftCard\GiftCardConfig;

class SalesOrderPreChecker implements SalesOrderPreCheckerInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    protected $giftCardReader;

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface
     */
    protected $giftCardDecisionRuleChecker;

    /**
     * @var \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface
     */
    protected $giftCardValueProvider;

    /** @var \Spryker\Zed\GiftCard\GiftCardConfig */
    protected $giftCardConfig;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker
     * @param \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface $giftCardValueProvider
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(
        GiftCardReaderInterface $giftCardReader,
        GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker,
        GiftCardValueProviderPluginInterface $giftCardValueProvider,
        GiftCardConfig $giftCardConfig
    ) {
        $this->giftCardReader = $giftCardReader;
        $this->giftCardDecisionRuleChecker = $giftCardDecisionRuleChecker;
        $this->giftCardValueProvider = $giftCardValueProvider;
        $this->giftCardConfig = $giftCardConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    public function precheckSalesOrderGiftCards(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $result = true;
        if (!$this->hasGiftCardPayments($quoteTransfer)) {
            return;
        }

        $validPayments = new ArrayObject();

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if (!($paymentTransfer->getPaymentProvider() === $this->giftCardConfig->getPaymentProviderName())) {
                $validPayments[] = $paymentTransfer;
                continue;
            }

            $paymentTransfer->requireGiftCard();
            $giftCardTransfer = $paymentTransfer->getGiftCard();

            $errors = $this->checkGiftCard($giftCardTransfer, $quoteTransfer, $paymentTransfer);

            if ($errors->count() === 0) {
                $validPayments[] = $paymentTransfer;
                continue;
            }

            foreach ($errors as $error) {
                $result = false;
                $checkoutResponse->addError($error);
            }
        }

        $quoteTransfer->setPayments($validPayments);

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasGiftCardPayments(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if ($paymentTransfer->getPaymentProvider() === $this->giftCardConfig->getPaymentProviderName()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CheckoutErrorTransfer[]
     */
    protected function checkGiftCard(GiftCardTransfer $giftCardTransfer, QuoteTransfer $quoteTransfer, PaymentTransfer $paymentTransfer)
    {
        $result = new ArrayObject();

        if (!$this->giftCardDecisionRuleChecker->isApplicable($giftCardTransfer, $quoteTransfer)) {
            $error = new CheckoutErrorTransfer();
            $error->setMessage('Gift Card ' . $giftCardTransfer->getCode() . ' already used');
            $error->setErrorCode(SharedGiftCardConfig::ERROR_GIFT_CARD_ALREADY_USED);

            $result[] = $error;
        }

        if ($this->giftCardValueProvider->getValue($giftCardTransfer) < $paymentTransfer->getAmount()) {
            $error = new CheckoutErrorTransfer();
            $error->setMessage('Gift Card ' . $giftCardTransfer->getCode() . ' used amount too high');
            $error->setErrorCode(SharedGiftCardConfig::ERROR_GIFT_CARD_AMOUNT_TOO_HIGH);

            $result[] = $error;
        }

        return $result;
    }
}
