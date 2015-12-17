<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Tax\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateCollectionTransfer;
use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use Spryker\Zed\Tax\Business\Model\Exception\MissingTaxRateException;

/**
 * @method TaxBusinessFactory getFactory()
 */
class TaxFacade extends AbstractFacade
{

    /**
     * @throws PropelException
     *
     * @return TaxRateCollectionTransfer
     */
    public function getTaxRates()
    {
        return $this->getFactory()->getReaderModel()->getTaxRates();
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return TaxRateTransfer
     */
    public function getTaxRate($id)
    {
        return $this->getFactory()->getReaderModel()->getTaxRate($id);
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     *
     * @return bool
     */
    public function taxRateExists($id)
    {
        return $this->getFactory()->getReaderModel()->taxRateExists($id);
    }

    /**
     * @throws PropelException
     *
     * @return TaxSetCollectionTransfer
     */
    public function getTaxSets()
    {
        return $this->getFactory()->getReaderModel()->getTaxSets();
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return TaxSetTransfer
     */
    public function getTaxSet($id)
    {
        return $this->getFactory()->getReaderModel()->getTaxSet($id);
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     *
     * @return bool
     */
    public function taxSetExists($id)
    {
        return $this->getFactory()->getReaderModel()->taxSetExists($id);
    }

    /**
     * @param TaxRateTransfer $taxRate
     *
     * @throws PropelException
     *
     * @return TaxRateTransfer
     */
    public function createTaxRate(TaxRateTransfer $taxRate)
    {
        return $this->getFactory()->getWriterModel()->createTaxRate($taxRate);
    }

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return int
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer)
    {
        return $this->getFactory()->getWriterModel()->updateTaxRate($taxRateTransfer);
    }

    /**
     * @param TaxSetTransfer $taxSet
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return TaxSetTransfer
     */
    public function createTaxSet(TaxSetTransfer $taxSet)
    {
        return $this->getFactory()->getWriterModel()->createTaxSet($taxSet);
    }

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return int
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer)
    {
        return $this->getFactory()->getWriterModel()->updateTaxSet($taxSetTransfer);
    }

    /**
     * @param int $taxSetId
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return int
     */
    public function addTaxRateToTaxSet($taxSetId, TaxRateTransfer $taxRateTransfer)
    {
        return $this->getFactory()->getWriterModel()->addTaxRateToTaxSet($taxSetId, $taxRateTransfer);
    }

    /**
     * @param int $taxSetId
     * @param int $taxRateId
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return int
     */
    public function removeTaxRateFromTaxSet($taxSetId, $taxRateId)
    {
        return $this->getFactory()->getWriterModel()->removeTaxRateFromTaxSet($taxSetId, $taxRateId);
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxRate($id)
    {
        return $this->getFactory()->getWriterModel()->deleteTaxRate($id);
    }

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxSet($id)
    {
        return $this->getFactory()->getWriterModel()->deleteTaxSet($id);
    }

}
