<?php

namespace SprykerFeature\Zed\Discount\Business\Model;

use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class DecisionRuleEngine implements DecisionRuleInterface
{
    /**
     * @param SpyDiscount $discountEntity
     * @param DiscountableContainerInterface $discountableContainer
     * @param DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     * @return bool
     */
    public function evaluate(
        SpyDiscount $discountEntity,
        DiscountableContainerInterface $discountableContainer,
        array $decisionRulePlugins
    ) {
        $errors = [];
        $result = new ModelResult();
        foreach ($decisionRulePlugins as $plugin) {
            $errors = array_merge($errors, $plugin->check($discountEntity, $discountableContainer)->getErrors());
        }

        $result->addErrors($errors);

        return $result;
    }
}
