<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\RuleConditionTransfer;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

class GrandtotalDecisionRulePlugin extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\RuleConditionTransfer $quoteTransfer
     */
    public function isSatisfiedBy(RuleConditionTransfer $ruleConditionTransfer)
    {
        return $this->getFacade()->isGrandTotalDecisionRuleSatisfiedBy($ruleConditionTransfer);
    }
}
