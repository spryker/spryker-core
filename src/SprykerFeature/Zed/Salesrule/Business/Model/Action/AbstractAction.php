<?php

namespace SprykerFeature\Zed\Salesrule\Business\Model\Action;

use SprykerFeature\Shared\Calculation\Transfer\Discount;
use SprykerFeature\Shared\Calculation\Transfer\DiscountCollection;
use SprykerFeature\Shared\Calculation\Transfer\Expense;
use SprykerFeature\Shared\Sales\Transfer\Order;
use SprykerFeature\Shared\Sales\Transfer\OrderItem;
use SprykerFeature\Shared\Sales\Transfer\OrderItemCollection;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleTableMap;
use SprykerFeature\Zed\Salesrule\Persistence\Propel\SpySalesrule;

abstract class AbstractAction
{

    const TYPE_COUPON_DISCOUNT = 'type_coupon_discount';
    const TYPE_SALESRULE_DISCOUNT = 'type_salesrule_discount';

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderItemCollection
     */
    protected $items;

    /**
     * @var SpySalesrule
     */
    protected $salesrule;

    /**
     * @var string
     */
    protected $discountType;

    /**
     * @var array
     */
    protected $usedCodes;

    /**
     * @param Order $order
     * @param SpySalesrule $salesrule
     * @param array $usedCodes
     */
    public function __construct(Order $order, SpySalesrule $salesrule, array $usedCodes = null)
    {
        $this->order = $order;
        $this->items = $order->getItems();
        $this->salesrule = $salesrule;
        $this->usedCodes = $usedCodes;
    }

    /**
     * @param SpySalesrule $salesrule
     *
     * @return string
     */
    protected function getDiscountType(SpySalesrule $salesrule)
    {
        if ($this->facadeSalesrule->isCouponDiscount($salesrule)) {
            return self::TYPE_COUPON_DISCOUNT;
        }

        return self::TYPE_SALESRULE_DISCOUNT;
    }

    /**
     * @param $element
     * @param $amount
     * @throws \Exception
     */
    protected function setDiscountAmount($element, $amount)
    {
        $discountType = $this->getDiscountType($this->salesrule);

        if ($this->salesrule->getScope() == SpySalesruleTableMap::COL_SCOPE_GLOBAL) {
            if ($discountType != self::TYPE_COUPON_DISCOUNT &&  $discountType != self::TYPE_SALESRULE_DISCOUNT) {
                throw new \Exception('Unknown discount type: ' . $discountType);
            }

            /**
             * it is not allowed to give discounts to an order item which would cause a price to pay that is 0 or less!!
             */
            if (($this->getTotalDiscountAmount($element) + $amount) >= $element->getGrossPrice()) {
                return;
            }

            $discounts = $this->getDiscounts($element);
            $discount = $this->getDiscount($amount);
            $discounts->add($discount);
            $element->setDiscounts($discounts);
        } elseif ($this->salesrule->getScope() == SpySalesruleTableMap::COL_SCOPE_LOCAL) {
            if ($discountType != self::TYPE_COUPON_DISCOUNT &&  $discountType != self::TYPE_SALESRULE_DISCOUNT) {
                throw new \Exception('Unknown discount type: ' . $discountType);
            }

            $discounts = $this->getDiscounts($element);
            $discount = $this->getDiscount($amount);
            $discounts = $this->replaceLowestItemDiscount($discounts, $discount);
            $element->setDiscounts($discounts);
        } else {
            throw new \Exception('Unknown scope: ' . $this->salesrule->getScope());
        }
    }

    /**
     * @param $discountableElement
     * @return DiscountCollection
     */
    protected function getDiscounts($discountableElement)
    {
        $discounts = $discountableElement->getDiscounts();
        if (!$discounts instanceof DiscountCollection) {
            $discounts = Locator::getInstance()->sales()->transferPriceDiscountCollection();
        }
        return $discounts;
    }

    /**
     * @param int $amount
     *
     * @return Discount
     */
    protected function getDiscount($amount)
    {
        $discountType = $this->getDiscountType($this->salesrule);
        $discount = Locator::getInstance()->sales()->transferPriceDiscount();
        $discount->setType($discountType);
        $discount->setSalesruleId($this->salesrule->getIdSalesrule());
        $discount->setScope($this->salesrule->getScope());
        $discount->setAmount($amount);
        $discount->setDisplayName($this->salesrule->getDisplayName());
        if (!empty($this->usedCodes)) {
            $discount->setUsedCodes($this->usedCodes);
        }
        return $discount;
    }

    /**
     * @param \SprykerFeature\Shared\Calculation\Transfer\DiscountCollection $discounts
     * @param Discount $replacementDiscount
     * @return \SprykerFeature\Shared\Calculation\Transfer\DiscountCollection
     */
    protected function replaceLowestItemDiscount(DiscountCollection $discounts, Discount $replacementDiscount)
    {
        if ($discounts->count() == 0) {
            $discounts->add($replacementDiscount);
            return $discounts;
        }

        $newDiscounts = Locator::getInstance()->sales()->transferPriceDiscountCollection();

        foreach ($discounts as $discount) {
            /* @var Discount $discount */
            if ($discount->getAmount() < $replacementDiscount->getAmount() && $discount->getScope() == SpySalesruleTableMap::COL_SCOPE_LOCAL && $replacementDiscount->getScope() == SprykerFeature\Zed\Salesrule\Persistence\Propel\Map\SpySalesruleTableMap::COL_SCOPE_LOCAL) {
                $newDiscounts->add($replacementDiscount);
            } else {
                $newDiscounts->add($discount);
            }
        }
        return $newDiscounts;
    }

    /**
     * @param OrderItem|Expense $discountableElement
     * @return int
     */
    protected function getTotalDiscountAmount($discountableElement)
    {
        $discounts = $this->getDiscounts($discountableElement);
        $totalDiscountAmount = 0;

        foreach ($discounts as $discount) {
            /* @var Discount $discount */
            $totalDiscountAmount += $discount->getAmount();
        }
        return $totalDiscountAmount;
    }

    /**
     * @return SpySalesrule
     */
    protected function loadSalesrule()
    {
        return $this->salesrule;
    }

    /**
     * @abstract
     * @return int
     */
    abstract public function execute();
}
