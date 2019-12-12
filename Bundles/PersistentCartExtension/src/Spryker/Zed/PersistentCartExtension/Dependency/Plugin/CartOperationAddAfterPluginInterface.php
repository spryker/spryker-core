<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;

interface CartOperationAddAfterPluginInterface
{
    /**
     * Specification:
     * - Executes after items were added to persistence cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $persistentCartChangeTransfer
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    public function execute(
        PersistentCartChangeTransfer $persistentCartChangeTransfer,
        QuoteResponseTransfer $quoteResponseTransfer
    ): void;
}
