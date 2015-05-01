<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\DiscountTotalsInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseTotalsInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Totals extends AbstractTransfer implements TotalsInterface
{
    /**
     * @var int
     */
    protected $subtotal = 0;

    /**
     * @var int
     */
    protected $subtotalWithoutItemExpenses = 0;

    /**
     * @var ExpenseTotalsInterface
     */
    protected $expenses = 'Calculation\\ExpenseTotals';

    /**
     * @var DiscountTotalsInterface
     */
    protected $discount = 'Calculation\\DiscountTotals';

    /**
     * @var TaxInterface
     */
    protected $tax = 'Calculation\\Tax';

    /**
     * @var int
     */
    protected $grandTotalWithDiscounts = 0;

    /**
     * @var int
     */
    protected $grandTotal = 0;

    /**
     * @param int $subtotal
     *
     * @return $this
     */
    public function setSubtotal($subtotal)
    {
        $this->subtotal = $subtotal;
        $this->addModifiedProperty('subtotal');
        return $this;
    }

    /**
     * @return int
     */
    public function getSubtotal()
    {
        return $this->subtotal;
    }

    /**
     * @param int $subtotalWithoutItemExpenses
     *
     * @return $this
     */
    public function setSubtotalWithoutItemExpenses($subtotalWithoutItemExpenses)
    {
        $this->subtotalWithoutItemExpenses = $subtotalWithoutItemExpenses;
        $this->addModifiedProperty('subtotalWithoutItemExpenses');

        return $this;
    }

    /**
     * @return int
     */
    public function getSubtotalWithoutItemExpenses()
    {
        return $this->subtotalWithoutItemExpenses;
    }

    /**
     * @param ExpenseTotalsInterface $expenses
     *
     * @return $this
     */
    public function setExpenses(ExpenseTotalsInterface $expenses)
    {
        $this->expenses = $expenses;
        $this->addModifiedProperty('expenses');

        return $this;
    }

    /**
     * @return ExpenseTotalsInterface
     */
    public function getExpenses()
    {
        return $this->expenses;
    }

    /**
     * @param DiscountTotalsInterface $discount
     *
     * @return $this
     */
    public function setDiscount(DiscountTotalsInterface $discount)
    {
        $this->discount = $discount;
        $this->addModifiedProperty('discount');

        return $this;
    }

    /**
     * @return DiscountTotalsInterface
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param TaxInterface $tax
     *
     * @return $this
     */
    public function setTax(TaxInterface $tax)
    {
        $this->tax = $tax;
        $this->addModifiedProperty('tax');

        return $this;
    }

    /**
     * @return TaxInterface
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param int $grandTotalWithDiscounts
     *
     * @return $this
     */
    public function setGrandTotalWithDiscounts($grandTotalWithDiscounts)
    {
        $this->grandTotalWithDiscounts = $grandTotalWithDiscounts;
        $this->addModifiedProperty('grandTotal');

        return $this;
    }

    /**
     * @return int
     */
    public function getGrandTotalWithDiscounts()
    {
        return $this->grandTotalWithDiscounts;
    }

    /**
     * @param int $grandTotal
     *
     * @return $this
     */
    public function setGrandTotal($grandTotal)
    {
        $this->grandTotal = $grandTotal;
        $this->addModifiedProperty('grandTotalWithoutDiscounts');

        return $this;
    }

    /**
     * @return int
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }
}
