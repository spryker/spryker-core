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
     * @return TaxRateCollectionTransfer
     * @throws PropelException
     */
    public function getTaxRates();

    /**
     * @param int $id
     *
     * @return TaxRateTransfer
     * @throws PropelException
     * @throws ResourceNotFoundException
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
     * @return TaxSetCollectionTransfer
     * @throws PropelException
     */
    public function getTaxSets();

    /**
     * @param int $id
     *
     * @return TaxSetTransfer
     * @throws PropelException
     * @throws ResourceNotFoundException
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
