<?php

namespace SprykerFeature\Zed\Tax\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateCollectionTransfer;
use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException;

/**
 * @method TaxDependencyContainer getDependencyContainer()
 */
class TaxFacade extends AbstractFacade
{

    /**
     * @return TaxRateCollectionTransfer
     * @throws PropelException
     */
    public function getTaxRates()
    {
        return $this->getDependencyContainer()->getReaderModel()->getTaxRates();
    }

    /**
     * @param int $id
     *
     * @return TaxRateTransfer
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function getTaxRate($id)
    {
        return $this->getDependencyContainer()->getReaderModel()->getTaxRate($id);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws PropelException
     */
    public function taxRateExists($id)
    {
        return $this->getDependencyContainer()->getReaderModel()->taxRateExists($id);
    }

    /**
     * @return TaxSetCollectionTransfer
     * @throws PropelException
     */
    public function getTaxSets()
    {
        return $this->getDependencyContainer()->getReaderModel()->getTaxSets();
    }

    /**
     * @param int $id
     *
     * @return TaxSetTransfer
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function getTaxSet($id)
    {
        return $this->getDependencyContainer()->getReaderModel()->getTaxSet($id);
    }

    /**
     * @param int $id
     *
     * @return bool
     * @throws PropelException
     */
    public function taxSetExists($id)
    {
        return $this->getDependencyContainer()->getReaderModel()->taxSetExists($id);
    }

    /**
     * @param TaxRateTransfer $taxRate
     *
     * @return int
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRate)
    {
        return $this->getDependencyContainer()->getWriterModel()->createTaxRate($taxRate);
    }

    /**
     * @param TaxRateTransfer $taxRate
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        return $this->getDependencyContainer()->getWriterModel()->updateTaxRate($taxRateTransfer);
    }

    /**
     * @param TaxSetTransfer $taxSet
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function createTaxSet(TaxSetTransfer $taxSet)
    {
        return $this->getDependencyContainer()->getWriterModel()->createTaxSet($taxSet);
    }

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        return $this->getDependencyContainer()->getWriterModel()->updateTaxSet($taxSetTransfer);
    }

    /**
     * @param int $taxSetId
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function addTaxRateToTaxSet($taxSetId, TaxRateTransfer $taxRateTransfer)
    {
        return $this->getDependencyContainer()->getWriterModel()->addTaxRateToTaxSet($taxSetId, $taxRateTransfer);
    }

    /**
     * @param int $taxSetId
     * @param int $taxRateId
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function removeTaxRateFromTaxSet($taxSetId, $taxRateId)
    {
        return $this->getDependencyContainer()->getWriterModel()->removeTaxRateFromTaxSet($taxSetId, $taxRateId);
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
