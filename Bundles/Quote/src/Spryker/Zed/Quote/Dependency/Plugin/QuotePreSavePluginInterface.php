<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

// TODO: what's the use case?
interface QuotePreSavePluginInterface
{
    /**
     * Specification:
     * - Plugin is triggered before quote is saved.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function preSave(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
