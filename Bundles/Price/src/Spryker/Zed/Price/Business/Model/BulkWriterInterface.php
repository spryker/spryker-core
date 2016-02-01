<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\SpyPriceType;
use Propel\Runtime\Exception\PropelException;

interface BulkWriterInterface
{

    /**
     * @param string $name
     *
     * @throws \Exception
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceType
     */
    public function createPriceType($name);

    /**
     * @param PriceProductTransfer $priceProductTransfer
     */
    public function setPriceForProduct(PriceProductTransfer $priceProductTransfer);

    /**
     * @param PriceProductTransfer $transferPriceProduct
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct);

    public function flush();

}
