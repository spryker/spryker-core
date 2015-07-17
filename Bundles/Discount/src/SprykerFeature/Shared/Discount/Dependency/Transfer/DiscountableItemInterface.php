<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

interface DiscountableItemInterface
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
