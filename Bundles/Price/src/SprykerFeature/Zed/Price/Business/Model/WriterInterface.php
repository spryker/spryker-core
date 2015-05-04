<?php

namespace SprykerFeature\Zed\Price\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceType;
use Propel\Runtime\Exception\PropelException;

interface WriterInterface
{
    /**
     * @param string $name
     * @return SpyPriceType
     * @throws \Exception
     * @throws PropelException
     */
    public function createPriceType($name);

    /**
     * @param Product $transferPriceProduct
     */
    public function setPriceForProduct(Product $transferPriceProduct);

    /**
     * @param Product $transferPriceProduct
     */
    public function createPriceForProduct(Product $transferPriceProduct);
}
