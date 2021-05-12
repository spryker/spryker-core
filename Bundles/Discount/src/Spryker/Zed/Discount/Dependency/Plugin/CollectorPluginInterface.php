<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface CollectorPluginInterface
{
    /**
     * Specification:
     * - Collects items to which discount have to be applied, ClauseTransfer holds query string parameters,
     * - Uses Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface to compare item value with ClauseTransfer.
     * - Returns array of discountable items with reference to original CalculatedDiscountTransfer, which is modified by reference by distributor.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer[]
     */
    public function collect(QuoteTransfer $quoteTransfer, ClauseTransfer $clauseTransfer);

    /**
     * Specification:
     * - Name of field as used in query string
     *
     * @api
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Specification:
     * - Data types used by this field. (string, integer, list)
     *
     * @api
     *
     * @return string[]
     */
    public function acceptedDataTypes();
}
