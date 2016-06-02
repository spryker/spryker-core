<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\DecisionRule;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Library\Currency\CurrencyManagerInterface;
use Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface;

class ItemPriceDecisionRule implements DecisionRuleInterface
{

    /**
     * @var \Spryker\Zed\Discount\Business\QueryString\ComparatorOperators
     */
    protected $comparators;

    /**
     * @var \Spryker\Shared\Library\Currency\CurrencyManagerInterface
     */
    protected $currencyManager;

    /**
     * @param \Spryker\Zed\Discount\Business\QueryString\ComparatorOperatorsInterface $comparators
     * @param \Spryker\Shared\Library\Currency\CurrencyManagerInterface $currencyManager
     */
    public function __construct(
        ComparatorOperatorsInterface $comparators,
        CurrencyManagerInterface $currencyManager
    ) {
        $this->comparators = $comparators;
        $this->currencyManager = $currencyManager;
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

        $amountInCents =  $this->currencyManager->convertDecimalToCent($clauseTransfer->getValue());
        $clauseTransfer->setValue($amountInCents);

        return $this->comparators->compare($clauseTransfer, $currentItemTransfer->getUnitGrossPrice());
    }

}
