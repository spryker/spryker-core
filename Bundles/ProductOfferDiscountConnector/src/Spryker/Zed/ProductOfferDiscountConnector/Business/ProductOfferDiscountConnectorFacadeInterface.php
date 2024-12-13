<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductOfferDiscountConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expects `ItemTransfer.productOfferReference` to be set.
     * - Checks if the item's product offer reference matches the clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isProductOfferReferenceSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ): bool;

    /**
     * Specification:
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires one of either `QuoteTransfer.item.unitNetPrice` or `QuoteTransfer.item.unitGrossPrice` to be set depending on price mode.
     * - Collects discountable items from the given quote by items' product offer references.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function getDiscountableItemsByProductOfferReference(
        QuoteTransfer $quoteTransfer,
        ClauseTransfer $clauseTransfer
    ): array;
}
