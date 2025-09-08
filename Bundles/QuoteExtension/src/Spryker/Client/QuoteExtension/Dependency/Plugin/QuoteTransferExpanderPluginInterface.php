<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Use this plugin to expand QuoteTransfer with additional data after it is read from persistence.
 */
interface QuoteTransferExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided QuoteTransfer with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuote(QuoteTransfer $quoteTransfer);
}
