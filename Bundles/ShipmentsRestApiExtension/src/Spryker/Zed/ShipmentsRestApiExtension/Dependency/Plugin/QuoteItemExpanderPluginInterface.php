<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Plugin interface is used to expand quote items.
 *
 * Runs during `/checkout-data` and `/checkout` requests.
 */
interface QuoteItemExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands quote items.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function expandQuoteItems(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
