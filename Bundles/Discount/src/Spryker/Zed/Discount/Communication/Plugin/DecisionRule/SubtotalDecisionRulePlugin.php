<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\RuleConditionTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 */
class SubtotalDecisionRulePlugin extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\RuleConditionTransfer $ruleConditionTransfer
     *
     * @return bool
     */
    public function isSatisfiedBy(RuleConditionTransfer $ruleConditionTransfer)
    {
        return $this->getFacade()->isSubTotalDecisionRuleSatisfiedBy($ruleConditionTransfer);
    }
}
