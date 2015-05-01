<?php

namespace SprykerFeature\Zed\Discount\Business\Writer;

use SprykerFeature\Shared\Discount\Transfer\DiscountDecisionRule;

/**
 * Class DiscountDecisionRuleManager
 * @package SprykerFeature\Zed\Discount\Business\Model
 */
class DiscountDecisionRuleWriter extends AbstractWriter
{
    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    /**
     * @param DiscountDecisionRule $discountDecisionRuleTransfer
     * @return mixed
     */
    public function create(DiscountDecisionRule $discountDecisionRuleTransfer)
    {
        $discountDecisionRuleEntity =$this->locator->discount()->entitySpyDiscountDecisionRule();
        $discountDecisionRuleEntity->fromArray($discountDecisionRuleTransfer->toArray());
        $discountDecisionRuleEntity->save();

        return $discountDecisionRuleEntity;
    }

    /**
     * @param DiscountDecisionRule $discountDecisionRuleTransfer
     * @return array|mixed|\SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function update(DiscountDecisionRule $discountDecisionRuleTransfer)
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
