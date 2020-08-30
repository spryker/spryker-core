<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DiscountCalculationToDiscountInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function calculateDiscounts(QuoteTransfer $quoteTransfer);

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateDiscounts(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer;
}
