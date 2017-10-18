<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model;

use Generated\Shared\Transfer\PriceFilterTransfer;

interface ReaderInterface
{

    /**
     * @return \Generated\Shared\Transfer\PriceTypeTransfer[]
     */
    public function getPriceTypes();

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return int
     */
    public function getPriceBySku($sku, $priceTypeName = null);

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return int
     */
    public function getPriceFor(PriceFilterTransfer $priceFilterTransfer);

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPricesBySku($sku);

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPrices($idProductAbstract);

    /**
     * @param int $idProductConcrete
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductConcretePrices($idProductConcrete, $idProductAbstract);

    /**
     * @param string $priceTypeNameName
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceType
     */
    public function getPriceTypeByName($priceTypeNameName);

    /**
     * @param string $sku
     * @param string|null $priceTypeName
     *
     * @return bool
     */
    public function hasValidPrice($sku, $priceTypeName = null);

    /**
     * @param \Generated\Shared\Transfer\PriceFilterTransfer $priceFilterTransfer
     *
     * @return bool
     */
    public function hasValidPriceFor(PriceFilterTransfer $priceFilterTransfer);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductAbstract($sku);

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function hasProductConcrete($sku);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductAbstractIdBySku($sku);

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\Product\Business\Exception\MissingProductException
     *
     * @return int
     */
    public function getProductConcreteIdBySku($sku);

    /**
     * @param string $sku
     * @param string $priceTypeName
     * @param string $currencyIsoCode
     * @return int
     */
    public function getProductPriceIdBySku($sku, $priceTypeName, $currencyIsoCode);

    /**
     * @param string|null $priceType
     *
     * @return string
     */
    public function handleDefaultPriceType($priceType = null);

    /**
     * @param string $sku
     *
     * @return array
     */
    public function findPricesBySkuGrouped($sku);

}
