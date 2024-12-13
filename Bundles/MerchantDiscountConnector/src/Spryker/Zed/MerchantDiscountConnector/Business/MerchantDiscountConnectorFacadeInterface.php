<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface MerchantDiscountConnectorFacadeInterface
{
    /**
     * Specification:
     * - Expects `ItemTransfer.merchantReference` to be set.
     * - Checks if `ItemTransfer.merchantReference` matches clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isMerchantReferenceSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer): bool;

    /**
     * Specification:
     * - Requires `QuoteTransfer.priceMode` to be set.
     * - Requires either `QuoteTransfer.item.unitNetPrice` or `QuoteTransfer.item.unitGrossPrice` to be set depending on price mode.
     * - Collects discountable items from the given quote by item merchant reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collectDiscountableItemsByMerchantReference(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array;

    /**
     * Specification:
     * - Reads the collection of merchants from Persistence.
     * - Returns associative array [merchant reference => merchant name].
     *
     * @api
     *
     * @return array<string, string>
     */
    public function getMerchantNamesIndexedByMerchantReference(): array;
}
