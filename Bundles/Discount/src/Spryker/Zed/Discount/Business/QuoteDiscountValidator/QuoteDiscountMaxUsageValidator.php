<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteDiscountValidator;

use ArrayObject;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidator;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;

class QuoteDiscountMaxUsageValidator extends AbstractQuoteDiscountValidator
{
    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     */
    public function __construct(
        DiscountQueryContainerInterface $discountQueryContainer
    ) {
        $this->discountQueryContainer = $discountQueryContainer;
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

        if ($this->hasVouchersThatExceedNumberOfUses($voucherDiscounts)) {
            $message = (new MessageTransfer())
                ->setValue(VoucherValidator::REASON_VOUCHER_CODE_LIMIT_REACHED);
            $this->addError($message, 'general fail', $checkoutResponseTransfer);
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[]|\ArrayObject $voucherDiscounts
     *
     * @return bool
     */
    protected function hasVouchersThatExceedNumberOfUses(ArrayObject $voucherDiscounts): bool
    {
        $count = $this->discountQueryContainer
            ->queryVouchersExceededMaxNumberOfUsage(
                $this->getVoucherCodes($voucherDiscounts)
            )
            ->count();

        return (bool)$count;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[]|\ArrayObject $voucherDiscounts
     *
     * @return string[]
     */
    protected function getVoucherCodes(ArrayObject $voucherDiscounts)
    {
        $result = [];
        foreach ($voucherDiscounts as $voucherDiscount) {
            $result[] = $voucherDiscount->getVoucherCode();
        }

        return $result;
    }
}
