<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\CartCode;

use ArrayObject;
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        if ($this->hasCandidate($quoteTransfer, $cartCode)) {
            return $quoteTransfer;
        }

        $voucherDiscount = new DiscountTransfer();
        $voucherDiscount->setVoucherCode($cartCode);

        return $quoteTransfer->addVoucherDiscount($voucherDiscount);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
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

        return $quoteTransfer->setUsedNotAppliedVoucherCodes($usedNotAppliedVoucherCodeResultList);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearCartCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setVoucherDiscounts(new ArrayObject());
        $quoteTransfer->setUsedNotAppliedVoucherCodes([]);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function findOperationResponseMessage(QuoteTransfer $quoteTransfer, string $cartCode): ?MessageTransfer
    {
        $voucherApplySuccessMessageTransfer = $this->findVoucherApplySuccessMessage($quoteTransfer, $cartCode);
        if ($voucherApplySuccessMessageTransfer) {
            return $voucherApplySuccessMessageTransfer;
        }

        $nonApplicableErrorMessageTransfer = $this->findNonApplicableErrorMessage($quoteTransfer, $cartCode);
        if ($nonApplicableErrorMessageTransfer) {
            return $nonApplicableErrorMessageTransfer;
        }

        return null;
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
    protected function findVoucherApplySuccessMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
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
    protected function findNonApplicableErrorMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
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
