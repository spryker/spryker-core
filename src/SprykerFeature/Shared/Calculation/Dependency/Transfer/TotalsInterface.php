<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

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
     * @param ExpenseTotalsInterface $expenses
     *
     * @return $this
     */
    public function setExpenses(ExpenseTotalsInterface $expenses);

    /**
     * @return ExpenseTotalsInterface
     */
    public function getExpenses();

    /**
     * @param DiscountTotalsInterface $discount
     *
     * @return $this
     */
    public function setDiscount(DiscountTotalsInterface $discount);

    /**
     * @return DiscountTotalsInterface
     */
    public function getDiscount();

    /**
     * @param TaxInterface $tax
     *
     * @return $this
     */
    public function setTax(TaxInterface $tax);

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