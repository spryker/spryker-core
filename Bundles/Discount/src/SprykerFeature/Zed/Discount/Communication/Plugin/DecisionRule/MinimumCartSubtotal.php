<?php

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRuleEntity;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class MinimumCartSubtotal extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{
    /**
     * @param DiscountEntity $discountEntity
     * @param OrderInterface $container
     * @param DecisionRuleEntity $decisionRuleEntity
     * @return $this|ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        OrderInterface $container,
        DecisionRuleEntity $decisionRuleEntity = null
    ) {
        return $this->getDependencyContainer()
            ->getDiscountFacade()
            ->isMinimumCartSubtotalReached($container, $decisionRuleEntity);
    }
}
