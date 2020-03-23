<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface DecisionRulePluginInterface
{
    /**
     * Specification:
     *
     * - Makes decision on given Quote or Item transfer.
     * - Uses Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface to compare item value with ClauseTransfer.
     * - Returns false when not matching.
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
     * Name of field as used in query string
     *
     * @api
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Data types used by this field. (string, integer, list)
     *
     * @api
     *
     * @return string[]
     */
    public function acceptedDataTypes();
}
