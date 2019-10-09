<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\CartCode;

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
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, $code): QuoteTransfer
    {
        if ($this->hasCandidate($quoteTransfer, $code)) {
            return $quoteTransfer;
        }

        $voucherDiscount = new DiscountTransfer();
        $voucherDiscount->setVoucherCode($code);

        $quoteTransfer->addVoucherDiscount($voucherDiscount);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, $code): QuoteTransfer
    {
        $voucherDiscountsIterator = $quoteTransfer->getVoucherDiscounts()->getIterator();
        foreach ($quoteTransfer->getVoucherDiscounts() as $key => $voucherDiscountTransfer) {
            if ($voucherDiscountTransfer->getVoucherCode() === $code) {
                $voucherDiscountsIterator->offsetUnset($key);
            }

            if (!$voucherDiscountsIterator->valid()) {
                break;
            }
        }

        return $this->unsetNotAppliedVoucherCode($code, $quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearAllCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer->setVoucherDiscounts(new ArrayObject());
        $quoteTransfer->setUsedNotAppliedVoucherCodes([]);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function getOperationResponseMessage(QuoteTransfer $quoteTransfer, $code): ?MessageTransfer
    {
        $voucherApplySuccessMessageTransfer = $this->getVoucherApplySuccessMessage($quoteTransfer, $code);
        if ($voucherApplySuccessMessageTransfer) {
            return $voucherApplySuccessMessageTransfer;
        }

        $nonApplicableErrorMessageTransfer = $this->getNonApplicableErrorMessage($quoteTransfer, $code);
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
    protected function getVoucherApplySuccessMessage(QuoteTransfer $quoteTransfer, string $code): ?MessageTransfer
    {
        if ($this->isVoucherFromPromotionDiscount($quoteTransfer, $code)) {
            return null;
        }

        if ($this->isVoucherCodeApplied($quoteTransfer, $code)) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setValue(static::GLOSSARY_KEY_VOUCHER_APPLY_SUCCESSFUL);
            $messageTransfer->setType(static::MESSAGE_TYPE_SUCCESS);

            return $messageTransfer;
        }

        return null;
    }

    /**
     * @param string $code
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function unsetNotAppliedVoucherCode(string $code, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $usedNotAppliedVoucherCodeResultList = array_filter(
            $quoteTransfer->getUsedNotAppliedVoucherCodes(),
            function ($usedNotAppliedVoucherCode) use ($code) {
                return $usedNotAppliedVoucherCode != $code;
            }
        );

        $quoteTransfer->setUsedNotAppliedVoucherCodes($usedNotAppliedVoucherCodeResultList);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return bool
     */
    protected function isVoucherFromPromotionDiscount(QuoteTransfer $quoteTransfer, string $code): bool
    {
        foreach ($quoteTransfer->getUsedNotAppliedVoucherCodes() as $codeUsed) {
            if ($codeUsed === $code) {
                return true;
            }
        }

        return false;
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
        foreach ($quoteTransfer->getUsedNotAppliedVoucherCodes() as $notAppliedVoucherCode) {
            if ($notAppliedVoucherCode !== $code) {
                return true;
            }
        }

        return false;
    }
}
