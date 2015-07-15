<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Collector;

use Generated\Shared\Discount\OrderInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

class Expense implements CollectorInterface
{
    /**
     * @ param OrderInterface $container
     * @param CalculableInterface $container
     *
     * @return OrderInterface[]
     */
    public function collect(CalculableInterface $container)
    //public function collect(OrderInterface $container)
    {
        $discountableExpenses = [];

        foreach ($container->getCalculableObject()->getExpenses() as $expense) {
            $discountableExpenses[] = $expense;
        }

        return $discountableExpenses;
    }
}
