<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscount as DiscountEntity;

interface DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountEntity $discountEntity
     * @param CalculableInterface $discountableContainer
     *
     * @return ModelResult
     */
    public function check(
        DiscountEntity $discountEntity,
        CalculableInterface $discountableContainer
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
