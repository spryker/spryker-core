<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;

interface DiscountableItemInterface extends CalculableItemInterface
{

    /**
     * @return float
     */
    public function getGrossPrice();

    /**
     * @return DiscountItemInterface[]|\ArrayObject
     */
    public function getDiscounts();

    /**
     * @param \ArrayObject $collection
     *
     * @return $this
     */
    public function setDiscounts(\ArrayObject $collection);

    /**
     * @return DiscountableOptionsInterface[]|\ArrayObject
     */
    public function getOptions();

    /**
     * @return DiscountableExpenseInterface[]|\ArrayObject
     */
    public function getExpenses();
}
