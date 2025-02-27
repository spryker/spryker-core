<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartReorderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Use this plugin interface to provide a strategy for providing a quote for reorder.
 */
interface CartReorderQuoteProviderStrategyPluginInterface
{
    /**
     * Specification:
     * - Checks if the strategy is applicable for the provided reorder request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(CartReorderRequestTransfer $cartReorderRequestTransfer): bool;

    /**
     * Specification:
     * - Provides a quote for reorder.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(CartReorderRequestTransfer $cartReorderRequestTransfer): QuoteTransfer;
}
