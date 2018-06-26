<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductQueryContainerInterface
{
    const COL_GROSS_PRICE = 'gross_price';
    const COL_NET_PRICE = 'net_price';

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceType($name);

    /**
     * @api
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function queryAllPriceTypes();

    /**
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductAbstract($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);

    /**
     * @api
     *
     * @param int $idAbstractProduct
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceEntityForProductAbstractById($idAbstractProduct, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);

    /**
     * @api
     *
     * @param int $idPriceProduct
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceProductStoreByProductCurrencyStore($idPriceProduct, $idCurrency, $idStore);

    /**
     * @api
     *
     * @param int $idPriceProductStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceProductStoreById($idPriceProductStore);

    /**
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractBySkuForStore($sku, $idStore);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractById($idProductAbstract);

    /**
     * @api

     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProduct();

    /**
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);

    /**
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteBySkuForStore($sku, $idStore);

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteById($idProductConcrete);

    /**
     * @api
     *
     * @param int $idPriceProduct
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductEntity($idPriceProduct);

    /**
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idPriceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductForConcreteProductBy($idProductConcrete, $idPriceType);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductForAbstractProduct($idProductAbstract, $idPriceType);
}
