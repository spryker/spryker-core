<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
     * @throws PropelException
     *
     * @return TaxRateTransfer
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return int
     */
    public function updateTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return TaxSetTransfer
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     * @throws MissingTaxRateException
     *
     * @return int
     */
    public function updateTaxSet(TaxSetTransfer $taxSetTransfer);

    /**
     * @param int $taxSetId
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return int
     */
    public function addTaxRateToTaxSet($taxSetId, TaxRateTransfer $taxRateTransfer);

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
