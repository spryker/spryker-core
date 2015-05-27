<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\OrderInterface;

class Expense implements CollectorInterface
{
    /**
     * @param OrderInterface $container
     * @return OrderInterface[]
     */
    public function collect(OrderInterface $container)
    {
        $discountableExpenses = [];
        $expenses = $container->getExpenses();

        foreach ($expenses as $expense) {
            $discountableExpenses[] = $expense;
        }

        return $discountableExpenses;
    }
}
