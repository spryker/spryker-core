<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Discount\Dependency\Plugin\DiscountDecisionRulePluginInterface;

interface DecisionRuleInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     * @param DiscountDecisionRulePluginInterface[] $decisionRulePlugins
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function evaluate(
        DiscountTransfer $discountTransfer,
        CalculableInterface $discountableContainer,
        array $decisionRulePlugins
    );

}
