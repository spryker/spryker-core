<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
use Spryker\Zed\Kernel\Business\ModelResult;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $discountableContainer
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
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
