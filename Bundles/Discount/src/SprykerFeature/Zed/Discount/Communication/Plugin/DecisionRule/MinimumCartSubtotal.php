<?php

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRuleEntity;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class MinimumCartSubtotal extends AbstractDecisionRule implements
    DiscountDecisionRulePluginInterface
{
    /**
     * @param DiscountEntity $discountEntity
     * @param DiscountableContainerInterface $container
     * @param DecisionRuleEntity $decisionRuleEntity
     * @return $this|ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        DiscountableContainerInterface $container,
        DecisionRuleEntity $decisionRuleEntity = null
    ) {
        return $this->getDependencyContainer()
            ->getDiscountFacade()
            ->isMinimumCartSubtotalReached($container, $decisionRuleEntity);
    }
}
