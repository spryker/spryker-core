<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\PriceProduct\Persistence\SpyPriceType;

interface PriceProductQueryContainerInterface
{

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
     * @param string $priceType
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductAbstract($sku, $priceType, $idCurrency, $idStore);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractBySku($sku);

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
     * @param string $priceType
     * @param int $idCurrency
     * @param int $idStore
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductConcrete($sku, $priceType, $idCurrency, $idStore);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteBySku($sku);

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

}
