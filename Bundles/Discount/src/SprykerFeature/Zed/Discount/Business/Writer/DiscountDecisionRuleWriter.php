<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use Generated\Shared\Transfer\DiscountDiscountDecisionRuleTransfer;
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
     * @param DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer
     * @return mixed
     */
    public function create(DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer)
    {
        $discountDecisionRuleEntity =$this->locator->discount()->entitySpyDiscountDecisionRule();
        $discountDecisionRuleEntity->fromArray($discountDecisionRuleTransfer->toArray());
        $discountDecisionRuleEntity->save();

        return $discountDecisionRuleEntity;
    }

    /**
     * @param DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer
     * @return array|mixed|SpyDiscountDecisionRule
     * @throws PropelException
     */
    public function update(DiscountDiscountDecisionRuleTransfer $discountDecisionRuleTransfer)
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
