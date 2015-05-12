<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\CalculationDiscountTotalsTransfer;
use Generated\Shared\Transfer\CalculationTaxTransfer;
use SprykerEngine\Shared\Transfer\TransferInterface;

interface TotalsInterface extends TransferInterface
{
    /**
     * @param int $subtotal
     *
     * @return $this
     */
    public function setSubtotal($subtotal);

    /**
     * @return int
     */
    public function getSubtotal();

    /**
     * @param int $subtotalWithoutItemExpenses
     *
     * @return $this
     */
    public function setSubtotalWithoutItemExpenses($subtotalWithoutItemExpenses);

    /**
     * @return int
     */
    public function getSubtotalWithoutItemExpenses();

    /**
     * @param \ArrayObject $expenses
     *
     * @return $this
     */
    public function setExpenses(\ArrayObject $expenses);

    /**
     * @return ExpenseTotalsInterface
     */
    public function getExpenses();

    /**
     * @param CalculationDiscountTotalsTransfer $discount
     *
     * @return $this
     */
    public function setDiscount(CalculationDiscountTotalsTransfer $discount);

    /**
     * @return DiscountTotalsInterface
     */
    public function getDiscount();

    /**
     * @param CalculationTaxTransfer $tax
     *
     * @return $this
     */
    public function setTax(CalculationTaxTransfer $tax);

    /**
     * @return TaxInterface
     */
    public function getTax();

    /**
     * @param int $grandTotalWithDiscounts
     *
     * @return $this
     */
    public function setGrandTotalWithDiscounts($grandTotalWithDiscounts);

    /**
     * @return int
     */
    public function getGrandTotalWithDiscounts();

    /**
     * @param int $grandTotal
     *
     * @return $this
     */
    public function setGrandTotal($grandTotal);

    /**
     * @return int
     */
    public function getGrandTotal();
}
