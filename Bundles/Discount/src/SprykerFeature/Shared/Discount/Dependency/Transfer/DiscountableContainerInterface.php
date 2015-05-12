<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

use Generated\Shared\Transfer\CalculationTotalsTransfer;
use Generated\Shared\Transfer\OrderItemsTransfer;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;

interface DiscountableContainerInterface extends CalculableContainerInterface
{

    /**
     * @return OrderItemsTransfer[]|DiscountableItemInterface[]
     */
    public function getItems();

    /**
     * @return \ArrayObject|DiscountItemInterface[]
     */
    public function getDiscounts();

    /**
     * @param \ArrayObject $collection
     *
     * @return $this
     */
    public function setDiscounts(\ArrayObject $collection);

    /**
     * @return \ArrayObject|DiscountableExpenseInterface[]
     */
    public function getExpenses();

    /**
     * @return CalculationTotalsTransfer
     */
    public function getTotals();

    /**
     * @return string[]
     */
    public function getCouponCodes();

    /**
     * @param string $couponCode
     *
     * @return $this
     */
    public function addCouponCode($couponCode);

    /**
     * @param array|string[] $couponCodes
     *
     * @return $this
     */
    public function setCouponCodes(array $couponCodes);
}
