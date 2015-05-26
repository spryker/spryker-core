<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;
use SprykerFeature\Zed\Tax\Business\Model\Exception\MissingTaxRateException;

interface TaxWriterInterface
{

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return TaxRateTransfer
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @return TaxSetTransfer
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * @param int $taxSetId
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     */
    public function addTaxRateToTaxSet($taxSetId, TaxRateTransfer $taxRateTransfer);

    /**
     * @param int $taxSetId
     * @param int $taxRateId
     *
     * @return int
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     */
    public function removeTaxRateFromTaxSet($taxSetId, $taxRateId);

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxRate($id);

    /**
     * @param int $id
     *
     * @throws PropelException
     */
    public function deleteTaxSet($id);
}
