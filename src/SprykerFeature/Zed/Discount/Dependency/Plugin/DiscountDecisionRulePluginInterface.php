<?php

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRuleEntity;

interface DiscountDecisionRulePluginInterface
{
    /**
     * @param DiscountEntity $discountEntity
     * @param DiscountableContainerInterface $discountableContainer
     * @param DecisionRuleEntity $decisionRuleEntity
     * @return ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        DiscountableContainerInterface $discountableContainer,
        DecisionRuleEntity $decisionRuleEntity = null
    );

    /**
     * @param array $context
     */
    public function setContext(array $context);

    /**
     * @return array
     */
    public function getContext();
}
