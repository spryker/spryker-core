<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Implement this plugin interface to check if price mode can be updated.
 */
interface CurrentPriceModePreCheckPluginInterface
{
    /**
     * Specification:
     * - Checks if the provided price mode is valid to be set into the Quote.
     *
     * @api
     *
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isPriceModeChangeAllowed(string $priceMode, QuoteTransfer $quoteTransfer): bool;
}
