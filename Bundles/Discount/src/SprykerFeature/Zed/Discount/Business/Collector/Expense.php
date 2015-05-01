<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

class Expense implements CollectorInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableContainerInterface[]
     */
    public function collect(DiscountableContainerInterface $container)
    {
        $discountableExpenses = [];
        $expenses = $container->getExpenses();

        foreach ($expenses as $expense) {
            $discountableExpenses[] = $expense;
        }

        return $discountableExpenses;
    }
}
