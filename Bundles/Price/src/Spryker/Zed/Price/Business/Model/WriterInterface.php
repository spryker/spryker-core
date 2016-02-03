<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;

interface WriterInterface
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
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     */
    public function setPriceForProduct(PriceProductTransfer $transferPriceProduct);

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     */
    public function createPriceForProduct(PriceProductTransfer $priceProductTransfer);

}
