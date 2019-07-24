<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;
use Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface;

class ItemPriceDecisionRule implements DecisionRuleInterface
{
    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected $comparators;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface
     */
    protected $moneyValueConverter;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparators
     * @param \Spryker\Zed\Discount\Business\QueryString\Converter\MoneyValueConverterInterface $moneyValueConverter
     */
    public function __construct(
        ComparatorOperatorsInterface $comparators,
        MoneyValueConverterInterface $moneyValueConverter
    ) {
        $this->comparators = $comparators;
        $this->moneyValueConverter = $moneyValueConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        $clonedClauseTransfer = clone $clauseTransfer;

        $this->moneyValueConverter->convertDecimalToCent($clonedClauseTransfer);

        return $this->comparators->compare($clonedClauseTransfer, $currentItemTransfer->getUnitPrice());
    }
}
