<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Discount\DiscountInterface;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountInterface $discountTransfer
     * @param CalculableInterface $discountableContainer
     *
     * @return ModelResult
     */
    public function check(
        DiscountInterface $discountTransfer,
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
