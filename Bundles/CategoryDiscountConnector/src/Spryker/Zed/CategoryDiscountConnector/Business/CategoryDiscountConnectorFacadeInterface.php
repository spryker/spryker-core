<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CategoryDiscountConnectorFacadeInterface
{
    /**
     * Specification:
     * - Requires `ItemTransfer.idProductAbstract` to be set.
     * - Requires `QuoteTransfer.item.idProductAbstract` to be set.
     * - Requires `ClauseTransfer.value` to be set.
     * - Checks if category matches clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCategorySatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool;

    /**
     * Specification:
     * - Requires `QuoteTransfer.item.idProductAbstract` to be set.
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires `ClauseTransfer.value` to be set.
     * - Requires one of either `QuoteTransfer.item.unitNetPrice` or `QuoteTransfer.item.unitGrossPrice` to be set depending on price mode.
     * - Collects discountable items from the given quote by item categories.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function getDiscountableItemsByCategory(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array;

    /**
     * Specification:
     * - Retrieves categories by the current locale from Persistence.
     * - Returns assoc array [category key => category name].
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getCategoryNamesIndexedByCategoryKey(): array;
}
