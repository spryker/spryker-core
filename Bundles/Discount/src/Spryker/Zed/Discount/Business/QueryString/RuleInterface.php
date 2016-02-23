<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\QueryString;

use Generated\Shared\Transfer\RuleConditionTransfer;

interface RuleInterface
{
    const COMPARATOR_EQUAL = '=';
    const COMPARATOR_NOT_EQUAL = '!=';
    const COMPARATOR_SMALLER = '<';
    const COMPARATOR_BIGGER = '>';
    const COMPARATOR_BIGGER_EQUAL = '>=';
    const COMPARATOR_LESS_EQUAL = '<=';

    /**
     * @param \Generated\Shared\Transfer\RuleConditionTransfer $ruleConditionTransfer
     *
     * @return boolean
     */
    public function isSatisfiedBy(RuleConditionTransfer $ruleConditionTransfer);
}
