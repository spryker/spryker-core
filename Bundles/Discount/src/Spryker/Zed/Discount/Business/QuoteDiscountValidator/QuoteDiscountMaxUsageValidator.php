<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteDiscountValidator;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidator;
use Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface;

class QuoteDiscountMaxUsageValidator implements QuoteDiscountValidatorInterface
{
    protected const ERROR_VOUCHER_CODE_LIMIT_REACHED = 399;

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface
     */
    protected $discountRepository;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountRepositoryInterface $discountRepository
     */
    public function __construct(DiscountRepositoryInterface $discountRepository)
    {
        $this->discountRepository = $discountRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function validate(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();
        if ($voucherDiscounts->count() === 0) {
            return true;
        }

        if ($this->hasVouchersExceedingUsageLimitByCodes($voucherDiscounts)) {
            $message = (new MessageTransfer())
                ->setValue(VoucherValidator::REASON_VOUCHER_CODE_LIMIT_REACHED);
            $this->addError($message, static::ERROR_VOUCHER_CODE_LIMIT_REACHED, $checkoutResponseTransfer);

            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[]|\ArrayObject $voucherDiscounts
     *
     * @return bool
     */
    protected function hasVouchersExceedingUsageLimitByCodes(ArrayObject $voucherDiscounts): bool
    {
        return (bool)$this->discountRepository
            ->findVouchersExceedingUsageLimitByCodes(
                $this->getVoucherCodes($voucherDiscounts)
            )
            ->count();
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[]|\ArrayObject $voucherDiscounts
     *
     * @return string[]
     */
    protected function getVoucherCodes(ArrayObject $voucherDiscounts)
    {
        $codes = [];
        foreach ($voucherDiscounts as $voucherDiscount) {
            $codes[] = $voucherDiscount->getVoucherCode();
        }

        return $codes;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $message
     * @param int $errorCode
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return void
     */
    protected function addError(MessageTransfer $message, int $errorCode, CheckoutResponseTransfer $checkoutResponseTransfer): void
    {
        $checkoutErrorTransfer = (new CheckoutErrorTransfer())
            ->setMessage($message->getValue())
            ->setErrorCode($errorCode);

        $checkoutResponseTransfer
            ->addError($checkoutErrorTransfer)
            ->setIsSuccess(false)
            ->setIsExternalRedirect(false);
    }
}
