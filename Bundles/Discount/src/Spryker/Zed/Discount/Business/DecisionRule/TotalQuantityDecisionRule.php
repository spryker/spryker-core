<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperators;

class TotalQuantityDecisionRule implements DecisionRuleInterface
{

    /**
     * @var ComparatorOperators
     */
    protected $comparators;

    /**
     * @param ComparatorOperators $comparators
     */
    public function __construct(ComparatorOperators $comparators)
    {
        $this->comparators = $comparators;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param ItemTransfer $currentItemTransfer
     * @param ClauseTransfer $clauseTransfer
     *
     * @return bool
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    )
    {
        $totalQuantity = 0;
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $totalQuantity += $itemTransfer->getQuantity();
        }

        return $this->comparators->compare($clauseTransfer, $totalQuantity);
    }
}
