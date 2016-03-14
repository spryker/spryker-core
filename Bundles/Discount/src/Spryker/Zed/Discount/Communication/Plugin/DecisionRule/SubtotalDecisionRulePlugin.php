<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Transfer\RuleConditionTransfer;
use Spryker\Zed\Discount\Communication\DiscountCommunicationFactory;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Discount\Business\DiscountFacade getFacade()
 * @method \Spryker\Zed\Discount\Communication\DiscountCommunicationFactory getFactory()
 */
class SubtotalDecisionRulePlugin extends AbstractPlugin implements DiscountDecisionRulePluginInterface
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
