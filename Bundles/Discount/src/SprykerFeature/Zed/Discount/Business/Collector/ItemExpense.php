<?php

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\OrderItemsTransfer;
use SprykerFeature\Shared\Discount\Dependency\Transfer\DiscountableContainerInterface;

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

        if ($items instanceof OrderItemsTransfer) {
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
