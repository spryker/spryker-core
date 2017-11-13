<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceProductFilterTransfer;

interface ReaderInterface
{
    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null);

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return int
     */
    public function getPriceFor(PriceProductFilterTransfer $priceProductFilterTransfer);

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku);

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract);

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceTypeName = null);

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceProductFilterTransfer $priceProductFilterTransfer);

    /**
     * @param string $sku
     * @param string $priceTypeName
     * @param string $currencyIsoCode
     *
     * @return int
     */
    public function getProductPriceIdBySku($sku, $priceTypeName, $currencyIsoCode);
}
