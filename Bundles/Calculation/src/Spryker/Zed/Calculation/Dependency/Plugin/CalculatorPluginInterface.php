<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @deprecated use CalculationPluginInterface instead
 */
interface CalculatorPluginInterface
{
    /**
     * This plugin makes calculations based on the given quote. The result is added to the quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer);
}
