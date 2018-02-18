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
use Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface;
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
     * @var GiftCardActualValueHydratorInterface
     */
    protected $giftCardActualValueHydrator;

    /**
     * @var \Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected $giftCardConfig;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker
     * @param GiftCardActualValueHydratorInterface $giftCardActualValueHydrator
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(
        GiftCardReaderInterface $giftCardReader,
        GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker,
        GiftCardActualValueHydratorInterface $giftCardActualValueHydrator,
        GiftCardConfig $giftCardConfig
    ) {
        $this->giftCardReader = $giftCardReader;
        $this->giftCardDecisionRuleChecker = $giftCardDecisionRuleChecker;
        $this->giftCardActualValueHydrator = $giftCardActualValueHydrator;
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
        if (!$this->hasGiftCardPayments($quoteTransfer)) {
            return true;
        }

        $result = true;
        $validPayments = new ArrayObject();

        foreach ($quoteTransfer->getPayments() as $paymentTransfer) {
            if (!($paymentTransfer->getPaymentProvider() === $this->giftCardConfig->getPaymentProviderName())) {
                $validPayments[] = $paymentTransfer;
                continue;
            }

            $errors = $this->checkSalesOrderGiftCardPayment($quoteTransfer, $paymentTransfer);

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
     * @param QuoteTransfer $quoteTransfer
     * @param PaymentTransfer $paymentTransfer
     *
     * @return ArrayObject|CheckoutErrorTransfer[]
     */
    protected function checkSalesOrderGiftCardPayment(QuoteTransfer $quoteTransfer, PaymentTransfer $paymentTransfer)
    {
        $paymentTransfer->requireGiftCard();
        $giftCardTransfer = $paymentTransfer->getGiftCard();
        $giftCardTransfer = $this->giftCardActualValueHydrator->hydrate($giftCardTransfer);

        return $this->checkGiftCard($giftCardTransfer, $quoteTransfer, $paymentTransfer);
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
        $errors = new ArrayObject();
        $errors = $this->checkGiftCardApplicability($giftCardTransfer, $quoteTransfer, $errors);
        $errors = $this->checkGiftCardAmount($giftCardTransfer, $paymentTransfer, $errors);
        $errors = $this->checkGiftCardCurrency($giftCardTransfer, $quoteTransfer, $errors);

        return $errors;
    }

    /**
     * @param GiftCardTransfer $giftCardTransfer
     * @param QuoteTransfer $quoteTransfer
     * @param \ArrayObject $errors
     *
     * @return \ArrayObject
     */
    protected function checkGiftCardApplicability(GiftCardTransfer $giftCardTransfer, QuoteTransfer $quoteTransfer, ArrayObject $errors)
    {
        if (!$this->giftCardDecisionRuleChecker->isApplicable($giftCardTransfer, $quoteTransfer)) {
            $error = new CheckoutErrorTransfer();
            $error->setMessage('Gift Card ' . $giftCardTransfer->getCode() . ' already used');
            $error->setErrorCode(SharedGiftCardConfig::ERROR_GIFT_CARD_ALREADY_USED);

            $errors[] = $error;
        }

        return $errors;
    }

    /**
     * @param GiftCardTransfer $giftCardTransfer
     * @param PaymentTransfer $paymentTransfer
     * @param ArrayObject $errors
     *
     * @return ArrayObject
     */
    protected function checkGiftCardAmount(GiftCardTransfer $giftCardTransfer, PaymentTransfer $paymentTransfer, ArrayObject $errors)
    {
        if ($giftCardTransfer->getActualValue() < $paymentTransfer->getAmount()) {
            $error = new CheckoutErrorTransfer();
            $error->setMessage('Gift Card ' . $giftCardTransfer->getCode() . ' used amount too high');
            $error->setErrorCode(SharedGiftCardConfig::ERROR_GIFT_CARD_AMOUNT_TOO_HIGH);

            $errors[] = $error;
        }

        return $errors;
    }

    /**
     * @param GiftCardTransfer $giftCardTransfer
     * @param QuoteTransfer $quoteTransfer
     * @param ArrayObject $errors
     *
     * @return ArrayObject
     */
    protected function checkGiftCardCurrency(GiftCardTransfer $giftCardTransfer, QuoteTransfer $quoteTransfer, ArrayObject $errors)
    {
        if ($giftCardTransfer->getCurrencyIsoCode() !== $quoteTransfer->getCurrency()->getCode()) {
            $error = new CheckoutErrorTransfer();
            $error->setMessage('Gift Card ' . $giftCardTransfer->getCode() . ' has different currency');
            $error->setErrorCode(SharedGiftCardConfig::ERROR_GIFT_CARD_WRONG_CURRENCY);

            $errors[] = $error;
        }

        return $errors;
    }
}
