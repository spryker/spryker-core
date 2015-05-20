<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Propel\Runtime\Exception\PropelException;

interface TaxReaderInterface
{

    /**
     * @param int $id
     *
     * @return TaxRateTransfer
     * @throws PropelException
     * @throws \Exception
     */
    public function getTaxRate($id);

    /**
     * @param int $id
     *
     * @return bool
     * @throws PropelException
     */
    public function taxRateExists($id);

    /**
     * @param int $id
     *
     * @return TaxSetTransfer
     * @throws PropelException
     * @throws \Exception
     */
    public function getTaxSet($id);

    /**
     * @param int $id
     *
     * @return bool
     * @throws PropelException
     */
    public function taxSetExists($id);
}