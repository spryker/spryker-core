<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;

interface ReaderInterface
{
    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int|null
     */
    public function findPriceBySku($sku, $priceTypeName = null);

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return int
     */
    public function findPriceFor(PriceProductFilterTransfer $priceProductFilterTransfer);

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer|null $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySkuForCurrentStore($sku, ?PriceProductDimensionTransfer $priceProductDimensionTransfer = null);

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices(
        $idProductConcrete,
        $idProductAbstract,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    );

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
