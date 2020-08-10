<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Calculation;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * @method \Spryker\Client\Calculation\CalculationFactory getFactory()
 */
interface CalculationClientInterface
{
    /**
     * Specification:
     *  - Makes Zed request.
     *  - Recalculates the given quote.
     *  - Executes `QuotePostRecalculatePluginInterface` stack of plugins.
     *  - Returns updated quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer);
}
