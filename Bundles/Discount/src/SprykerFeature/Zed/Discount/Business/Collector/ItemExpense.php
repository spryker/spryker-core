<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\DiscountCollectorInterface;
use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class ItemExpense implements CollectorInterface
{

    /**
     * @param CalculableInterface $container
     * @param DiscountCollectorInterface $discountCollectorTransfer
     *
     * @return OrderInterface[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorInterface $discountCollectorTransfer)
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
