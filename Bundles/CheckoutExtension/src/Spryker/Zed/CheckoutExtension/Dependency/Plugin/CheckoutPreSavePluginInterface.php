<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CheckoutExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides extension capabilities for actions executed before order will be saved to Persistence.
 */
interface CheckoutPreSavePluginInterface
{
    /**
     * Specification:
     * - Retrieves data from the quote object and saves it to Persistence before order has been saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preSave(QuoteTransfer $quoteTransfer);
}
