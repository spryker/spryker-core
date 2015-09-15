<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Plugin\DecisionRule;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Discount\Communication\DiscountDependencyContainer;

/**
 * @method DiscountDependencyContainer getDependencyContainer()
 */
class MinimumCartSubtotal extends AbstractDecisionRule implements DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountInterface $discountTransfer
     * @param CalculableInterface $container
     *
     * @return $this|ModelResult
     */
    public function check(
        DiscountInterface $discountTransfer,
        CalculableInterface $container
    ) {
        $decisionRuleEntity = $this->getContext()[self::KEY_ENTITY];
        return $this->getDependencyContainer()
            ->getDiscountFacade()
            ->isMinimumCartSubtotalReached($container, $decisionRuleEntity);
    }

}
