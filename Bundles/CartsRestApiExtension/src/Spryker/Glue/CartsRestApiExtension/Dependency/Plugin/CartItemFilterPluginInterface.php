<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Plugin exists to allow filtering of the `ItemTransfer`s.
 *
 * The resulting array should be filtered out `ItemTransfer`s that will become `items` REST resource.
 */
interface CartItemFilterPluginInterface
{
    /**
     * Specification:
     * - Filters out item transfers that should not be a separate `items` REST resource.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function filterCartItems(array $itemTransfers, QuoteTransfer $quoteTransfer): array;
}
