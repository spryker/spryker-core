<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteAfterCalculatePluginInterface
{
    /**
     * Specification:
     * - Allows to add custom logic after calculations for quote are executed.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function afterCalculate(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
