<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\OrderInterface;
use Generated\Shared\Discount\OrderItemsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class ItemExpense implements CollectorInterface
{
    /**
     * @param CalculableInterface $container
     *
     * @return OrderInterface[]
     */
    public function collect(CalculableInterface $container)
    {
        $discountableExpenses = [];

        foreach ($container->getCalculableObject()->getItems() as $item) {
            $expenses = $item->getExpenses();

            foreach ($expenses as $expense) {
                $discountableExpenses[] = $expense;
            }
        }

        return $discountableExpenses;
    }
}
