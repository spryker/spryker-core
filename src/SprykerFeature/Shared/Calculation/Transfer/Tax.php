<?php 

namespace SprykerFeature\Shared\Calculation\Transfer;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxItemCollectionInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TaxItemInterface;
use SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

class Tax extends AbstractTransfer implements TaxInterface
{
    /**
     * @var int
     */
    protected $totalAmount = 0;

    /**
     * @var TaxItemCollectionInterface
     */
    protected $taxRates = 'Calculation\\TaxItemCollection';

    /**
     * @param int $totalAmount
     *
     * @return $this
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
        $this->addModifiedProperty('totalAmount');

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param TaxItemCollectionInterface $taxRates
     *
     * @return $this
     */
    public function setTaxRates(TaxItemCollectionInterface $taxRates)
    {
        $this->taxRates = $taxRates;
        $this->addModifiedProperty('taxRates');

        return $this;
    }

    /**
     * @return TaxItemInterface[]|TaxItemCollectionInterface
     */
    public function getTaxRates()
    {
        return $this->taxRates;
    }

    /**
     * @param TaxItemInterface $taxRate
     *
     * @return $this
     */
    public function addTaxRate(TaxItemInterface $taxRate)
    {
        $this->taxRates->add($taxRate);
        $this->addModifiedProperty('taxRates');

        return $this;
    }

    /**
     * @param TaxItemInterface $taxRate
     *
     * @return $this
     */
    public function removeTaxRate(TaxItemInterface $taxRate)
    {
        $this->taxRates->remove($taxRate);
        $this->addModifiedProperty('taxRates');

        return $this;
    }
}
