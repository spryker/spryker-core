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
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @return mixed
     */
    public function create(DecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        $discountDecisionRuleEntity = $this->locator->discount()->entitySpyDiscountDecisionRule();
        $discountDecisionRuleEntity->fromArray($discountDecisionRuleTransfer->toArray());
        $discountDecisionRuleEntity->save();

        return $discountDecisionRuleEntity;
    }

    /**
     * @param DecisionRuleTransfer $discountDecisionRuleTransfer
     *
     * @throws PropelException
     *
     * @return array|mixed|SpyDiscountDecisionRule
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

}
