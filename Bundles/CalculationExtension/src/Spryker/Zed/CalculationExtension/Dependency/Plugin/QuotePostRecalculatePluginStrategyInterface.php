<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuotePostRecalculatePluginStrategyInterface
{
    /**
     * Specification:
     * - Executes various business logic after quote recalculation.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer;

    /**
     * Specification:
     * - Defines whether `execute` method needs to be called or not.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool;
}
