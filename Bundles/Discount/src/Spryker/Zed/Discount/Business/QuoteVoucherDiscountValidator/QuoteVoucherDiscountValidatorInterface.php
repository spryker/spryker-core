<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteVoucherDiscountValidator;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteVoucherDiscountValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function validateVoucherDiscounts(QuoteTransfer $quoteTransfer): bool;
}
