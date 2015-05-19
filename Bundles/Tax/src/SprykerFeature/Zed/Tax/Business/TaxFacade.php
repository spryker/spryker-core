<?php

namespace SprykerFeature\Zed\Tax\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

/**
 * @method TaxDependencyContainer getDependencyContainer()
 */
class TaxFacade extends AbstractFacade
{
    /**
     * @param int $id
     *
     * @return TaxRateTransfer
     * @throws PropelException
     */
    public function getTaxRate($id)
    {
        return $this->getDependencyContainer()->getReaderModel()->getTaxRate($id);
    }

    /**
     * @param int $id
     *
     * @return TaxSetTransfer
     * @throws PropelException
     */
    public function getTaxSet($id)
    {
        return $this->getDependencyContainer()->getReaderModel()->getTaxSet($id);
    }

    /**
     * @param TaxRateTransfer $taxRate
     *
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRate)
    {
        return $this->getDependencyContainer()->getWriterModel()->createTaxRate($taxRate);
    }

    /**
     * @param TaxSetTransfer $taxSet
     *
     * @throws PropelException
     */
    public function createTaxSet(TaxSetTransfer $taxSet)
    {
        return $this->getDependencyContainer()->getWriterModel()->createTaxSet($taxSet);
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxRate($id)
    {
        return $this->getDependencyContainer()->getWriterModel()->deleteTaxRate($id);
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxSet($id)
    {
        return $this->getDependencyContainer()->getWriterModel()->deleteTaxSet($id);
    }
}
