<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Propel\Runtime\Exception\PropelException;
use Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule;

class DiscountDecisionRuleWriter extends AbstractWriter
{

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function create(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        $discountDecisionRuleEntity = new SpyDiscountDecisionRule();
        $discountDecisionRuleEntity->fromArray($discountDecisionRuleTransfer->toArray());
        $discountDecisionRuleEntity->save();

        return $discountDecisionRuleEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Discount\Persistence\SpyDiscountDecisionRule
     */
    public function update(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        $queryContainer = $this->getQueryContainer();
        $discountDecisionRuleEntity = $queryContainer
            ->queryDiscountDecisionRule()
            ->findPk($discountDecisionRuleTransfer->getIdDiscountDecisionRule());
        $discountDecisionRuleEntity->fromArray($discountDecisionRuleTransfer->toArray());
        $discountDecisionRuleEntity->save();

        return $discountDecisionRuleEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\DecisionRuleTransfer $decisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule|null
     */
    public function saveDiscountDecisionRule(DecisionRuleTransfer $decisionRuleTransfer)
    {
        if ($decisionRuleTransfer->getIdDiscountDecisionRule() === null) {
            return $this->create($decisionRuleTransfer);
        }

        return $this->update($decisionRuleTransfer);
    }

}
