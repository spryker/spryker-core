<?php
/**
 * Created by PhpStorm.
 * User: matveyev
 * Date: 6/18/18
 * Time: 14:59
 */

namespace Spryker\Client\PriceProductStorage\Plugin;

use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductStoragePriceDimensionPluginInterface
{
    /**
     * Specification:
     *  - Returns prices from Storage for concrete product.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return PriceProductTransfer[]
     */
    public function findProductConcretePrices(int $idProductConcrete): array;

    /**
     * * Specification:
     *  - Returns prices from Storage for abstract product.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return PriceProductTransfer[]
     */
    public function findProductAbstractPrices(int $idProductAbstract): array;
}
