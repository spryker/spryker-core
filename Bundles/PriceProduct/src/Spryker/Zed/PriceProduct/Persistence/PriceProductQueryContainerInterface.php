<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery;

interface PriceProductQueryContainerInterface
{
    public const COL_GROSS_PRICE = 'gross_price';
    public const COL_NET_PRICE = 'net_price';

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceType($name): SpyPriceTypeQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceTypeQuery
     */
    public function queryAllPriceTypes(): SpyPriceTypeQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductAbstract($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idAbstractProduct
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceEntityForProductAbstractById(
        $idAbstractProduct,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): SpyPriceProductStoreQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idPriceProduct
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceProductStoreByProductCurrencyStore($idPriceProduct, $idCurrency, $idStore): SpyPriceProductStoreQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idPriceProductStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery
     */
    public function queryPriceProductStoreById($idPriceProductStore): SpyPriceProductStoreQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractBySkuForStore($sku, $idStore): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractById($idProductAbstract): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api

     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProduct();

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductConcrete($sku, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteBySkuForStore($sku, $idStore): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteById($idProductConcrete): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idPriceProduct
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductEntity($idPriceProduct): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductConcrete
     * @param int $idPriceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductForConcreteProductBy($idProductConcrete, $idPriceType): SpyPriceProductQuery;

    /**
     * Specification:
     * TODO: Add method specification.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idPriceType
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductForAbstractProduct($idProductAbstract, $idPriceType): SpyPriceProductQuery;
}
