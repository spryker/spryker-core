<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides extension capabilities to make decisions on given `QuoteTransfer` or `ItemTransfer`.
 */
interface DecisionRulePluginInterface
{
    /**
     * Specification:
     * - Makes decision on given `QuoteTransfer` or `ItemTransfer`.
     * - Uses {@link \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface} to compare item value with `ClauseTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    );

    /**
     * Specification:
     * - Name of the field, as used in query string.
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
