<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\OrderInterface;
use Generated\Shared\Discount\OrderItemsInterface;

class ItemExpense implements CollectorInterface
{
    /**
     * @param OrderInterface $container
     * @return OrderInterface[]
     */
    public function collect(OrderInterface $container)
    {
        $discountableExpenses = [];
        $items = $container->getItems();

        if ($items instanceof OrderItemsInterface) {
            foreach ($items->getOrderItems() as $item) {
                $expenses = $item->getExpenses();

                foreach ($expenses as $expense) {
                    $discountableExpenses[] = $expense;
                }
            }
        }

        return $discountableExpenses;
    }
}
