<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\Discount\DependencyDiscountableContainerInterfaceTransfer;

class ItemExpense implements CollectorInterface
{
    /**
     * @param DiscountableContainerInterface $container
     * @return DiscountableContainerInterface[]
     */
    public function collect(DiscountableContainerInterface $container)
    {
        $discountableExpenses = [];
        $items = $container->getItems();

        foreach ($items as $item) {
            $expenses = $item->getExpenses();

            foreach ($expenses as $expense) {
                $discountableExpenses[] = $expense;
            }
        }

        return $discountableExpenses;
    }
}
