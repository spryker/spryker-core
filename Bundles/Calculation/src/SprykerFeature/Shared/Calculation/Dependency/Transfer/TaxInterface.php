<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerEngine\Shared\Transfer\TransferInterface;

interface TaxInterface extends TransferInterface
{
    /**
     * @param int $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount($totalAmount);

    /**
     * @return int
     */
    public function getTotalAmount();

    /**
     * @param \ArrayObject $taxRates
     *
     * @return $this
     */
    public function setTaxRates(\ArrayObject $taxRates);

    /**
     * @return TaxItemInterface[]|\ArrayObject
     */
    public function getTaxRates();

    /**
     * @param TaxItemInterface $taxRate
     *
     * @return $this
     */
    public function addTaxRate(TaxItemInterface $taxRate);

    /**
     * @param TaxItemInterface $taxRate
     *
     * @return $this
     */
    public function removeTaxRate(TaxItemInterface $taxRate);
}
