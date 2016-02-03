<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Collector;

use Generated\Shared\Transfer\DiscountCollectorTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

class ItemExpense implements CollectorInterface
{

    /**
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $container
     * @param \Generated\Shared\Transfer\DiscountCollectorTransfer $discountCollectorTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function collect(CalculableInterface $container, DiscountCollectorTransfer $discountCollectorTransfer)
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
