<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;

interface DecisionRuleInterface
{
    /**
     * @param SpyDiscount $discountEntity
     * @param OrderInterface $discountableContainer
     * @param DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     * @return bool
     */
    public function evaluate(
        SpyDiscount $discountEntity,
        OrderInterface $discountableContainer,
        array $decisionRulePlugins
    );
}
