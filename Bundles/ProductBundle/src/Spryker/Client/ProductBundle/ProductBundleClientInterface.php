<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductBundleClientInterface
{
    /**
     * Specification:
     * - Groups bundled items with bundle.
     * - Groups based on SKU and/or their selected product options.
     *
     * @api
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $items
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $bundleItems
     *
     * @return array
     */
    public function getGroupedBundleItems(ArrayObject $items, ArrayObject $bundleItems);

    /**
     * Specification:
     * - Groups bundled items with bundle.
     * - Groups based on SKU and/or their selected product options.
     * - Returns iterable of items
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function getItemsWithBundlesItems(QuoteTransfer $quoteTransfer): array;

    /**
     * Specification:
     *  - Find bundled items in quote.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function findBundleItemsInQuote(QuoteTransfer $quoteTransfer, $sku, $groupKey): array;

    /**
     * Specification:
     * - Requires `CartChangeTransfer.quote` to be set.
     * - Requires `groupKey` and `quantity` to be set for each element in `CartChangeTransfer.items`.
     * - Replaces bundles in `CartChangeTransfer.items` with corresponding bundled items.
     * - Bundled items get into `CartChangeTransfer.items` united in one piece with a corresponding quantity,
     *   instead of being added individually with a quantity of 1. I.e. a bundle in `CartChangeTransfer.items`
     *   with a quantity of 3 will be replaced with groups of bundled items, each group also having a quantity of 3.
     * - Used when united bundled items approach is applied in cart.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function replaceBundlesWithUnitedItems(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer;
}
