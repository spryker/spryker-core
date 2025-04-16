<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCartExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * This plugin is triggered after the quote is merged.
 */
interface QuotePostMergePluginInterface
{
    /**
     * Specification:
     * - Executes after the quote is merged.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $persistentQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $currentQuoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function postMerge(QuoteTransfer $persistentQuoteTransfer, QuoteTransfer $currentQuoteTransfer): QuoteTransfer;
}
