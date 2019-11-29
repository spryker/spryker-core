<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\CartCode;

use ArrayObject;
use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class VoucherCartCode implements VoucherCartCodeInterface
{
    protected const GLOSSARY_KEY_VOUCHER_NON_APPLICABLE = 'cart.voucher.apply.non_applicable';
    protected const GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL = 'cart.voucher.apply.successful';

    protected const MESSAGE_TYPE_SUCCESS = 'success';
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function addCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();
        $cartCode = $cartCodeRequestTransfer->getCartCode();

        $cartCodeResponseTransfer = new CartCodeResponseTransfer();
        if ($this->hasCandidate($quoteTransfer, $cartCode)) {
            return $cartCodeResponseTransfer->setQuote($quoteTransfer);
        }

        $voucherDiscount = new DiscountTransfer();
        $voucherDiscount->setVoucherCode($cartCode);

        $quoteTransfer->addVoucherDiscount($voucherDiscount);

        return $cartCodeResponseTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function removeCartCode(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();
        $cartCode = $cartCodeRequestTransfer->getCartCode();
        $cartCodeResponseTransfer = new CartCodeResponseTransfer();
        $voucherDiscountsIterator = $quoteTransfer->getVoucherDiscounts()->getIterator();
        foreach ($quoteTransfer->getVoucherDiscounts() as $key => $voucherDiscountTransfer) {
            if ($voucherDiscountTransfer->getVoucherCode() === $cartCode) {
                $voucherDiscountsIterator->offsetUnset($key);
            }

            if (!$voucherDiscountsIterator->valid()) {
                break;
            }
        }

        $usedNotAppliedVoucherCodeResultList = array_filter(
            $quoteTransfer->getUsedNotAppliedVoucherCodes(),
            function (string $usedNotAppliedVoucherCode) use ($cartCode) {
                return $usedNotAppliedVoucherCode != $cartCode;
            }
        );

        $quoteTransfer->setUsedNotAppliedVoucherCodes($usedNotAppliedVoucherCodeResultList);

        return $cartCodeResponseTransfer->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function clearCartCodes(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();
        $quoteTransfer->setVoucherDiscounts(new ArrayObject());
        $quoteTransfer->setUsedNotAppliedVoucherCodes([]);

        return (new CartCodeResponseTransfer())->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeRequestTransfer $cartCodeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartCodeResponseTransfer
     */
    public function getOperationResponseMessage(CartCodeRequestTransfer $cartCodeRequestTransfer): CartCodeResponseTransfer
    {
        $cartCodeResponseTransfer = new CartCodeResponseTransfer();
        $quoteTransfer = $cartCodeRequestTransfer->getQuote();
        $cartCode = $cartCodeRequestTransfer->getCartCode();
        $voucherApplySuccessMessageTransfer = $this->getVoucherApplySuccessMessage($quoteTransfer, $cartCode);
        if ($voucherApplySuccessMessageTransfer) {
            return $cartCodeResponseTransfer->addMessage($voucherApplySuccessMessageTransfer);
        }

        $nonApplicableErrorMessageTransfer = $this->getNonApplicableErrorMessage($quoteTransfer, $cartCode);
        if ($nonApplicableErrorMessageTransfer) {
            return $cartCodeResponseTransfer->addMessage($nonApplicableErrorMessageTransfer);
        }

        return $cartCodeResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    protected function hasCandidate(QuoteTransfer $quoteTransfer, string $code): bool
    {
        foreach ($quoteTransfer->getVoucherDiscounts() as $voucherDiscount) {
            if ($voucherDiscount->getVoucherCode() === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function getVoucherApplySuccessMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
    {
        if ($this->isVoucherFromPromotionDiscount($quoteTransfer, $code) || !$this->isVoucherCodeApplied($quoteTransfer, $code)) {
            return null;
        }

        return (new MessageTransfer())
            ->setValue(static::GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL)
            ->setType(static::MESSAGE_TYPE_SUCCESS);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    protected function isVoucherFromPromotionDiscount(QuoteTransfer $quoteTransfer, string $code): bool
    {
        return in_array($code, $quoteTransfer->getUsedNotAppliedVoucherCodes(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    protected function isVoucherCodeApplied(QuoteTransfer $quoteTransfer, string $code): bool
    {
        foreach ($quoteTransfer->getVoucherDiscounts() as $discountTransfer) {
            if ($discountTransfer->getVoucherCode() === $code) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    protected function getNonApplicableErrorMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
    {
        if ($this->isVoucherCodeApplyFailed($quoteTransfer, $code)) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue(static::GLOSSARY_KEY_VOUCHER_NON_APPLICABLE);
            $messageTransfer->setType(static::MESSAGE_TYPE_ERROR);

            return $messageTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    protected function isVoucherCodeApplyFailed(QuoteTransfer $quoteTransfer, string $code): bool
    {
        return !in_array($code, $quoteTransfer->getUsedNotAppliedVoucherCodes(), true);
    }
}
