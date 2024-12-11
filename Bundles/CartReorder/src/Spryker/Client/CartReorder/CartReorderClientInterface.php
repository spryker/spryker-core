<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartReorder;

use Generated\Shared\Transfer\CartReorderRequestTransfer;
use Generated\Shared\Transfer\CartReorderResponseTransfer;

interface CartReorderClientInterface
{
    /**
     * Specification:
     * - Gets current quote and sets it to the `CartReorderRequestTransfer.quote`.
     * - Makes Zed request.
     * - Requires `CartReorderRequestTransfer.customerReference` to be set.
     * - Requires `CartReorderRequestTransfer.orderReference` to be set.
     * - Retrieves the customer order from the persistence layer using the provided order and customer references.
     * - Validates the reorder request by executing {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderRequestValidatorPluginInterface} plugins.
     * - Determines the `QuoteTransfer` to use by executing {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderQuoteProviderStrategyPluginInterface} plugins.
     *   If no plugins provide a `QuoteTransfer`, it will use the `CartReorderRequestTransfer.quote`.
     * - Filters items from the order by `CartReorderRequestTransfer.salesOrderItemIds`.
     * - Executes {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderOrderItemFilterPluginInterface} plugins to filter items by the provided reorder request.
     * - Executes {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPreReorderPluginInterface} plugins to perform any necessary actions before reordering items.
     * - Validates the reorder request by executing {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderValidatorPluginInterface} plugins.
     * - Executes {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderItemHydratorPluginInterface} plugins to hydrate the items in the reorder request.
     * - Executes {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartReorderPreAddToCartPluginInterface} plugins before adding reorder items to cart.
     * - Adds the hydrated items to the cart.
     * - Executes {@link \Spryker\Zed\CartReorderExtension\Dependency\Plugin\CartPostReorderPluginInterface} plugins to perform any necessary actions after reordering items.
     * - Returns a `CartReorderResponseTransfer` containing the updated `QuoteTransfer` with the reordered items.
     * - Updates the quote in the quote client.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartReorderRequestTransfer $cartReorderRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CartReorderResponseTransfer
     */
    public function reorder(CartReorderRequestTransfer $cartReorderRequestTransfer): CartReorderResponseTransfer;
}
