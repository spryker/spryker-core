<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DecisionRuleTransfer;
use Generated\Zed\Ide\AutoCompletion;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule;

class DiscountDecisionRuleWriter extends AbstractWriter
{

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return SpyDiscountDecisionRule
     */
    public function create(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        $discountDecisionRuleEntity = new SpyDiscountDecisionRule();
        $discountDecisionRuleEntity->fromArray($discountDecisionRuleTransfer->toArray());
        $discountDecisionRuleEntity->save();

        return $discountDecisionRuleEntity;
    }

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @throws PropelException
     *
     * @return SpyDiscountDecisionRule
     */
    public function update(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {

        $queryContainer = $this->getQueryContainer();
        $discountDecisionRuleEntity = $queryContainer
            ->queryDiscountDecisionRule()
            ->findPk($discountDecisionRuleTransfer->getIdDiscountDecisionRule())
        ;
        $discountDecisionRuleEntity->fromArray($discountDecisionRuleTransfer->toArray());
        $discountDecisionRuleEntity->save();

        return $discountDecisionRuleEntity;
    }

    /**
     * @param DecisionRuleTransfer $decisionRuleTransfer
     *
     * @return null|SpyDiscountDecisionRule
     */
    public function saveDiscountDecisionRule(DecisionRuleTransfer $decisionRuleTransfer)
    {
        if (null === $decisionRuleTransfer->getIdDiscountDecisionRule()) {
            return $this->create($decisionRuleTransfer);
        }

        return $this->update($decisionRuleTransfer);
    }

}
