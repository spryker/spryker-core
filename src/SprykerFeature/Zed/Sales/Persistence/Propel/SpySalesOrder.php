<?php

namespace SprykerFeature\Zed\Sales\Persistence\Propel;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrder as BaseSpySalesOrder;

/**
 * Skeleton subclass for representing a row from the 'spy_sales_order' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class SpySalesOrder extends BaseSpySalesOrder
{

    /**
     * @var TotalsInterface
     */
    protected $totals;

    /**
     * @var \Generated\Zed\Ide\AutoCompletion
     */
    protected $locator;

    public function __construct()
    {
        $this->locator = Locator::getInstance();
        parent::__construct();
    }

    /**
     * This is needed because propel reuses the entities. getTotals() did not work because the instance variable has
     * also been initialized before.
     * @param null $col
     */
    public function resetModified($col = null)
    {
        parent::resetModified($col);
        $this->totals = null;
    }

    /**
     * @param \Traversable $orderItems
     *
     * @return TotalsInterface
     */
    public function getTotals(\Traversable $orderItems = null)
    {
        if ($orderItems) {
            return $this->locator->calculation()->facade()->recalculateTotalsForEntity($this, $orderItems);
        }

        if (!$this->totals) {
            $this->totals = $this->locator->calculation()->facade()->recalculateTotalsForEntity($this);
        }

        return $this->totals;
    }

    /**
     * @return int
     */
    public function getGrandTotal()
    {
        if (!parent::getGrandTotal()) {
            return $this->getTotals()->getGrandTotalWithDiscounts();
        } else {
            return parent::getGrandTotal();
        }
    }

    /**
     * @return int
     */
    public function getSubtotal()
    {
        if (!parent::getSubtotal()) {
            return $this->getTotals()->getSubtotal();
        } else {
            return parent::getSubtotal();
        }
    }

    /**
     * @return int
     */
    public function getTaxAmount()
    {
        return $this->getTotals()->getTax()->getTotalAmount();
    }

    /**
     * @return int
     */
    public function getDiscountAmount()
    {
        return $this->getTotals()->getDiscount()->getTotalAmount();
    }

    /**
     * @return boolean
     */
    public function isGuestOrder()
    {
        return ($this->getFkCustomer() ? false : true);
    }
}
