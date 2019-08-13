<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface QuoteResetLockQuoteStorageStrategyPluginInterface
{
    /**
     * Specification:
     * - Makes zed request.
     * - Executes QuoteLockPreResetPluginInterface plugins before unlock.
     * - Unlocks quote by setting `isLocked` transfer property to false.
     * - Reloads all items in cart as new, it recreates all items transfer, reads new prices, options, bundles.
     * - Stores quote in session internally after zed request.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function resetQuoteLock(): QuoteResponseTransfer;

    /**
     * Specification:
     * - Gets quote storage strategy type.
     *
     * @api
     *
     * @return string
     */
    public function getStorageStrategy();
}
