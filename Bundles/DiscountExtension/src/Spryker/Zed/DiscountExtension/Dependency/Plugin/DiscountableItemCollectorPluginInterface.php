<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides extension capabilities for the collection of items to which discounts have to be applied.
 */
interface DiscountableItemCollectorPluginInterface
{
    /**
     * Specification:
     * - Collects items to which discount have to be applied, `ClauseTransfer` holds query string parameters.
     * - Uses {@link \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface} to compare item value with `ClauseTransfer`.
     * - Returns an array of discountable items with reference to the original `CalculatedDiscountTransfer`, which is modified by reference by the distributor.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return array<\Generated\Shared\Transfer\DiscountableItemTransfer>
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Name of the field as used in query string.
     *
     * @api
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Specification:
     * - Data types used by this field (string, number, list).
     *
     * @api
     *
     * @return array<string>
     */
    public function acceptedDataTypes();
}
