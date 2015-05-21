<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

interface TaxWriterInterface
{

    /**
     * @param TaxRateTransfer $taxRateTransfer
     *
     * @return int
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRateTransfer);

    /**
     * @param TaxSetTransfer $taxSetTransfer
     *
     * @return int
     * @throws PropelException
     */
    public function createTaxSet(TaxSetTransfer $taxSetTransfer);

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
