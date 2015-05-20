<?php

namespace SprykerFeature\Shared\Tax\Dependency\Transfer;

use Generated\Shared\Transfer\TaxTransfer;
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
     * @return TaxInterface[]|\ArrayObject
     */
    public function getTaxRates();

    /**
     * @param TaxTransfer $taxRate
     *
     * @return $this
     */
    public function addTaxRate(TaxTransfer $taxRate);
}