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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     */
    public function setPriceForProduct(PriceProductTransfer $priceProductTransfer);

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     */
    public function createPriceForProduct(PriceProductTransfer $transferPriceProduct);

    public function flush();

}
