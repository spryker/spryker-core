<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteVoucherDiscountValidator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\Voucher\VoucherValidator;

class QuoteVoucherDiscountValidator implements QuoteVoucherDiscountValidatorInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\Voucher\VoucherValidator
     */
    protected $voucherValidator;

    /**
     * @param \Spryker\Zed\Discount\Business\Voucher\VoucherValidator $voucherValidator
     */
    public function __construct(VoucherValidator $voucherValidator)
    {
        $this->voucherValidator = $voucherValidator;
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
            $isPassed = $isPassed && $this->voucherValidator->isUsable($discountTransfer->getVoucherCode());
        }

        return $isPassed;
    }
}
