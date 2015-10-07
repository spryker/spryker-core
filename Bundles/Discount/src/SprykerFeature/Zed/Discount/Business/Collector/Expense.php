<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class Expense implements CollectorInterface
{
    /**
     * @param CalculableInterface $container
     *
     * @return OrderInterface[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorInterface $discountCollectorTransfer)
    {
        $discountableExpenses = [];

        foreach ($container->getCalculableObject()->getExpenses() as $expense) {
            $discountableExpenses[] = $expense;
        }

        return $discountableExpenses;
    }
}
