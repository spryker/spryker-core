<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Persistence;

use Generated\Shared\Transfer\PriceProductTransfer;
use Orm\Zed\Price\Persistence\SpyPriceType;

interface PriceQueryContainerInterface
{

    /**
     * @api
     *
     * @param string $name
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryPriceType($name);

    /**
     * @api
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceTypeQuery
     */
    public function queryAllPriceTypes();

    /**
     * @api
     *
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductAbstract($sku, SpyPriceType $priceType);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractBySku($sku);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductAbstractById($idProductAbstract);

    /**
     * @api

     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProduct();

    /**
     * @api
     *
     * @param string $sku
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceEntityForProductConcrete($sku, SpyPriceType $priceType);

    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteBySku($sku);

    /**
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPricesForProductConcreteById($idProductConcrete);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductAbstract(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $transferPriceProduct
     * @param \Orm\Zed\Price\Persistence\SpyPriceType $priceType
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function querySpecificPriceForProductConcrete(PriceProductTransfer $transferPriceProduct, SpyPriceType $priceType);

    /**
     * @api
     *
     * @param int $idPriceProduct
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryPriceProductEntity($idPriceProduct);

    /**
     * @api
     *
     * @param int $idProduct
     *
     * @return \Orm\Zed\Price\Persistence\SpyPriceProductQuery
     */
    public function queryProductAbstractPriceByIdConcreteProduct($idProduct);

}
