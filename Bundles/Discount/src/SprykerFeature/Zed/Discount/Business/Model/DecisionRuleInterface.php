<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

interface DecisionRuleInterface
{

    /**
     * @param SpyDiscount $discountEntity
     *
     * @param CalculableInterface $discountableContainer
     * @param DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     *
     * @return bool
     */
    public function evaluate(
        SpyDiscount $discountEntity,
        CalculableInterface $discountableContainer,
        array $decisionRulePlugins
    );

}
