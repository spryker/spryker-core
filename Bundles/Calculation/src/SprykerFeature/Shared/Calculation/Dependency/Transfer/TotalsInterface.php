<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use Generated\Shared\Transfer\DiscountTotalsTransfer;
use Generated\Shared\Transfer\TaxTransfer;
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
     * @param DiscountTotalsTransfer $discount
     *
     * @return $this
     */
    public function setDiscount(DiscountTotalsTransfer $discount);

    /**
     * @return DiscountTotalsInterface
     */
    public function getDiscount();

    /**
     * @param TaxTransfer $tax
     *
     * @return $this
     */
    public function setTax(TaxTransfer $tax);

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
