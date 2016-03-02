<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
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
     *
     * @return void
     */
    public function setContext(array $context);

    /**
     * @return array
     */
    public function getContext();

}
