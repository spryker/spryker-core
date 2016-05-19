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
     * @param QuoteTransfer $quoteTransfer
     * @param ItemTransfer $itemTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return mixed
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    );


    /**
     * Name of field as used in query string
     *
     * @return string
     */
    public function getFieldName();

    /**
     * Data types used by this field. (string, integer, list)
     *
     * @return array
     */
    public function acceptedDataTypes();
}
