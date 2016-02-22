<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class Expense implements CollectorInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer)
    {
        $discountableExpenses = [];

        foreach ($container->getCalculableObject()->getExpenses() as $expense) {
            $discountableExpenses[] = $expense;
        }

        return $discountableExpenses;
    }

}
