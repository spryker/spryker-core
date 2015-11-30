<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
use SprykerEngine\Zed\Kernel\Business\ModelResult;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountDecisionRulePluginInterface
{

    /**
     * @param DiscountTransfer $discountTransfer
     * @param CalculableInterface $discountableContainer
     *
     * @return ModelResult
     */
    public function check(
        DiscountTransfer $discountTransfer,
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
