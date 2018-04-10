<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBundle;

use ArrayObject;
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
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $bundleItems
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
     * @return \Generated\Shared\Transfer\ItemTransfer[]
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
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function findBundleItemsInQuote(QuoteTransfer $quoteTransfer, $sku, $groupKey): array;
}
