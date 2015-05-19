<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

interface TaxReaderInterface {

    /**
     * @param int $id
     * @return TaxRateTransfer
     * @throws PropelException
     */
    public function getTaxRate($id);

    /**
     * @param int $id
     * @return TaxSetTransfer
     * @throws PropelException
     */
    public function getTaxSet($id);
}