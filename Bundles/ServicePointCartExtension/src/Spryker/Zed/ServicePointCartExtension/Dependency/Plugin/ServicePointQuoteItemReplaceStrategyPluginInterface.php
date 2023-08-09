<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePointCartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides replacement capabilities for items inside `QuoteTransfer`.
 */
interface ServicePointQuoteItemReplaceStrategyPluginInterface
{
    /**
     * Specification:
     * - Defines whether strategy is applicable or not.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool;

    /**
     * Specification:
     * - Executes replacement strategy.
     * - Returns back `QuoteResponseTransfer` with modified quote inside.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteResponseTransfer;
}
