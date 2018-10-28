<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteVoucherDiscountValidator;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Voucher\CheckoutVoucherValidator;

class QuoteVoucherDiscountValidator implements QuoteVoucherDiscountValidatorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\Voucher\CheckoutVoucherValidator
     */
    protected $checkoutVoucherValidator;

    /**
     * @param \Spryker\Zed\Discount\Business\Voucher\CheckoutVoucherValidator $checkoutVoucherValidator
     */
    public function __construct(CheckoutVoucherValidator $checkoutVoucherValidator)
    {
        $this->checkoutVoucherValidator = $checkoutVoucherValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function validateVoucherDiscounts(QuoteTransfer $quoteTransfer): bool
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();

        if ($voucherDiscounts->count() === 0) {
            return true;
        }

        $isPassed = true;

        foreach ($voucherDiscounts as $discountTransfer) {
            $isPassed = $isPassed && $this->checkoutVoucherValidator->isUsable($discountTransfer->getVoucherCode());
        }

        return $isPassed;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    public function getCheckoutResponseTransfer(): CheckoutResponseTransfer
    {
        return $this->checkoutVoucherValidator->getCheckoutResponseTransfer();
    }
}
