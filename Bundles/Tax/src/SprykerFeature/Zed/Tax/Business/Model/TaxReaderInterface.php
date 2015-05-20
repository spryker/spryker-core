<?php

namespace SprykerFeature\Zed\Tax\Business\Model;

use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxSet;
use SprykerFeature\Zed\Tax\Persistence\Propel\SpyTaxRate;
use Propel\Runtime\Exception\PropelException;

interface TaxReaderInterface
{

    /**
     * @param int $id
     *
     * @return SpyTaxRate
     * @throws PropelException
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
     * @return SpyTaxSet
     * @throws PropelException
     */
    public function getTaxSet($id);
}