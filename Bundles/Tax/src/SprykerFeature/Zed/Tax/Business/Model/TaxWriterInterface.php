<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

interface TaxWriterInterface {

    /**
     * @param TaxRateTransfer $taxRate
     * @return TaxRateTransfer
     * @throws PropelException
     */
    public function createTaxRate(TaxRateTransfer $taxRate);

    /**
     * @param TaxSetTransfer $taxSet
     * @return TaxSetTransfer
     * @throws PropelException
     */
    public function createTaxSet(TaxSetTransfer $taxSet);

    /**
     * @param int $id
     * @throws PropelException
     */
    public function deleteTaxRate($id);

    /**
     * @param int $id
     * @throws PropelException
     */
    public function deleteTaxSet($id);
}