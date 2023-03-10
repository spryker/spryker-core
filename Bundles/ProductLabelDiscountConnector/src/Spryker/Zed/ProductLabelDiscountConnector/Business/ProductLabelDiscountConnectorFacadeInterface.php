<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelDiscountConnector\Business;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface ProductLabelDiscountConnectorFacadeInterface
{
    /**
     * Specification:
     * - Builds all product variants by abstract sku.
     * - Looks for labels in any items.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelDiscountConnector\Business\ProductLabelDiscountConnectorFacadeInterface::isProductLabelSatisfiedByListClause} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isProductLabelSatisfiedBy(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Builds all product variants by abstract sku.
     * - Looks for labels in any variants.
     * - Collects all matching items.
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\ProductLabelDiscountConnector\Business\ProductLabelDiscountConnectorFacadeInterface::getDiscountableItemsCollection()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collectByProductLabel(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Retrieves product labels from Persistence.
     * - Returns an array with all label names.
     *
     * @api
     *
     * @return array<string, string>
     */
    public function findAllLabels();

    /**
     * Specification:
     * - Requires `QuoteTransfer.item.idProductAbstract` to be set.
     * - Collects discountable items from the given quote by items product labels.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function getDiscountableItemsCollection(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer): array;

    /**
     * Specification:
     * - Requires `ItemTransfer.idProductAbstract` to be set.
     * - Checks if product label matches clause.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     */
    public function isProductLabelSatisfiedByListClause(
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ): bool;
}
