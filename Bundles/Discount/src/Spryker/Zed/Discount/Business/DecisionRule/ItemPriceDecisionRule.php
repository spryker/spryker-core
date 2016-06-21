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
use Spryker\Zed\Discount\Business\QueryString\Converter\CurrencyConverterInterface;

class ItemPriceDecisionRule implements DecisionRuleInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface
     */
    protected $comparators;

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\Converter\CurrencyConverterInterface
     */
    protected $currencyConverter;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparators
     * @param \Spryker\Zed\Discount\Business\QueryString\Converter\CurrencyConverterInterface $currencyConverter
     */
    public function __construct(
        ComparatorOperatorsInterface $comparators,
        CurrencyConverterInterface $currencyConverter
    ) {
        $this->comparators = $comparators;
        $this->currencyConverter = $currencyConverter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $currentItemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @throws \Spryker\Zed\Discount\Business\Exception\ComparatorException
     *
     * @return bool
     *
     */
    public function isSatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $currentItemTransfer,
        ClauseTransfer $clauseTransfer
    ) {

        $this->currencyConverter->convertDecimalToCent($clauseTransfer);

        return $this->comparators->compare($clauseTransfer, $currentItemTransfer->getUnitGrossPrice());
    }
}
