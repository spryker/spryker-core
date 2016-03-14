<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RuleConditionTransfer;

abstract class AbstractComparableRule implements RuleInterface
{

    /**
     * @param \Generated\Shared\Transfer\RuleConditionTransfer $ruleConditionTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(RuleConditionTransfer $ruleConditionTransfer)
    {
        $quoteTransfer = $ruleConditionTransfer->getQuote();
        $inputValue = $ruleConditionTransfer->getInputValue();
        switch ($ruleConditionTransfer->getComparator()) {
            case RuleInterface::COMPARATOR_EQUAL:
                return $this->equal($quoteTransfer, $inputValue);
                break;
            case RuleInterface::COMPARATOR_NOT_EQUAL:
                return $this->notEqual($quoteTransfer, $inputValue);
                break;
            case RuleInterface::COMPARATOR_BIGGER:
                return $this->moreThan($quoteTransfer, $inputValue);
                break;
            case RuleInterface::COMPARATOR_SMALLER:
                return $this->lessThan($quoteTransfer, $inputValue);
                break;
            case RuleInterface::COMPARATOR_LESS_EQUAL:
                return $this->lessEqualThan($quoteTransfer, $inputValue);
                break;
            case RuleInterface::COMPARATOR_BIGGER_EQUAL:
                return $this->moreEqualThan($quoteTransfer, $inputValue);
                break;
        }

        return false;
    }


    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param mixed $inputValue
     *
     * @return bool
     */
    protected function equal(QuoteTransfer $quoteTransfer, $inputValue)
    {
        return $this->compareWith($quoteTransfer) === $inputValue;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param mixed $inputValue
     *
     * @return bool
     */
    protected function notEqual(QuoteTransfer $quoteTransfer, $inputValue)
    {
        return $this->compareWith($quoteTransfer) !== $inputValue;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param mixed $inputValue
     *
     * @return bool
     */
    protected function moreThan(QuoteTransfer $quoteTransfer, $inputValue)
    {
        return $this->compareWith($quoteTransfer) > $inputValue;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param mixed $inputValue
     *
     * @return bool
     */
    protected function lessThan(QuoteTransfer $quoteTransfer, $inputValue)
    {
        return $this->compareWith($quoteTransfer) < $inputValue;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param mixed $inputValue
     *
     * @return bool
     */
    protected function moreEqualThan(QuoteTransfer $quoteTransfer, $inputValue)
    {
        return $this->compareWith($quoteTransfer) >= $inputValue;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param mixed $inputValue
     *
     * @return bool
     */
    protected function lessEqualThan(QuoteTransfer $quoteTransfer, $inputValue)
    {
        return $this->compareWith($quoteTransfer) <= $inputValue;
    }

    /**
     * Concrete class should provide value against which comparision will happen.
     * Each comparator will use this value.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return mixed
     */
    abstract protected function compareWith(QuoteTransfer $quoteTransfer);

}
