<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Discount\OrderInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountDecisionRule as DecisionRuleEntity;

interface DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountEntity $discountEntity
     *
     * @param CalculableInterface $discountableContainer
     * @param DecisionRuleEntity $decisionRuleEntity
     *
     * @return ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        CalculableInterface $discountableContainer,
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
