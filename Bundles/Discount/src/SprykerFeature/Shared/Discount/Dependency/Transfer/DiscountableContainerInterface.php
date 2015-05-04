<?php

namespace SprykerFeature\Shared\Discount\Dependency\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;

interface DiscountableContainerInterface extends CalculableContainerInterface
{

    /**
     * @return DiscountableItemInterface[]
     */
    public function getItems();

    /**
     * @return DiscountItemInterface[]
     */
    public function getDiscounts();

    /**
     * @param DiscountableItemCollectionInterface $collection
     *
     * @return $this
     */
    public function setDiscounts(DiscountableItemCollectionInterface $collection);

    /**
     * @return DiscountableExpenseInterface[]
     */
    public function getExpenses();

    /**
     * @return DiscountTotalsInterface
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
