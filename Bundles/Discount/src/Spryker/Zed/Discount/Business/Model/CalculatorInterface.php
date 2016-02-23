<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;

interface CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $discountCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    public function calculate(array $discountCollection, QuoteTransfer $quoteTransfer);

}
