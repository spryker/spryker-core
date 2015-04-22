<?php
namespace SprykerFeature\Zed\Salesrule\Business\Model;

interface DiscountableItemInterface
{
    /**
     * @return \PropelObjectCollection|\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount[]
     */
    public function getDiscounts();

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount $discount
     */
    public function addDiscount(\SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesDiscount $discount);
}
