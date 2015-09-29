<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\DiscountInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

interface DecisionRuleInterface
{

    /**
     * @param DiscountInterface $discountTransfer
     * @param CalculableInterface $discountableContainer
     * @param DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     *
     * @return ModelResult
     */
    public function evaluate(
        DiscountInterface $discountTransfer,
        CalculableInterface $discountableContainer,
        array $decisionRulePlugins
    );

}
