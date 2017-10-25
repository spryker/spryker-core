<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;

interface CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discounts
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer[]
     */
    public function calculate(array $discounts, QuoteTransfer $quoteTransfer);
}
