<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\DiscountInterface;
use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount;
use SprykerEngine\Zed\Kernel\Business\ModelResult;

class DecisionRuleEngine implements DecisionRuleInterface
{

    /**
     * @param DiscountInterface $discountTransfer
     *
     * @param CalculableInterface $discountableContainer
     * @param DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     *
     * @return ModelResult
     */
    public function evaluate(
        DiscountInterface $discountTransfer,
        CalculableInterface $discountableContainer,
        array $decisionRulePlugins
    ) {
        $errors = [];
        $result = new ModelResult();
        foreach ($decisionRulePlugins as $plugin) {
            $errors = array_merge($errors, $plugin->check($discountTransfer, $discountableContainer)->getErrors());
        }

        $result->addErrors($errors);

        return $result;
    }

}
