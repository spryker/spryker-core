<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Tax\Business\Model;

use Generated\Shared\Transfer\TaxRateTransfer;
use Generated\Shared\Transfer\TaxSetTransfer;
use Generated\Shared\Transfer\TaxRateCollectionTransfer;
use Generated\Shared\Transfer\TaxSetCollectionTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Tax\Business\Model\Exception\ResourceNotFoundException;

interface TaxReaderInterface
{

    /**
     * @throws PropelException
     *
     * @return TaxRateCollectionTransfer
     */
    public function getTaxRates();

    /**
     * @param int $id
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return TaxRateTransfer
     */
    public function getTaxRate($id);

    /**
     * @param int $id
     *
     * @throws PropelException
     *
     * @return bool
     */
    public function taxRateExists($id);

    /**
     * @throws PropelException
     *
     * @return TaxSetCollectionTransfer
     */
    public function getTaxSets();

    /**
     * @param int $id
     *
     * @throws PropelException
     * @throws ResourceNotFoundException
     *
     * @return TaxSetTransfer
     */
    public function getTaxSet($id);

    /**
     * @param int $id
     *
     * @throws PropelException
     *
     * @return bool
     */
    public function taxSetExists($id);

}
