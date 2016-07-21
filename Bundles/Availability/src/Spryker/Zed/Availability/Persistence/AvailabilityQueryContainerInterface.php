<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

use Orm\Zed\Availability\Persistence\Base\SpyAvailabilityQuery;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;

interface AvailabilityQueryContainerInterface
{

    /**
     * @param string $sku
     *
     * @return SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku);

    /**
     * @param string $abstractSku
     *
     * @return SpyAvailabilityAbstractQuery
     */
    public function querySpyAvailabilityAbstractByAbstractSku($abstractSku);

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract);

    /**
     * @param int $idAvailabilityAbstract
     *
     * @return SpyAvailabilityQuery
     */
    public function querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract);

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function querySpyProductBySku($sku);

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return SpyAvailabilityAbstractQuery|SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     *
     * @return SpyAvailabilityAbstractQuery|SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale);

    /**
     * @param int $idLocale
     *
     * @return SpyAvailabilityAbstractQuery|SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdLocale($idLocale);

    /**
     * @param int $idLocale
     *
     * @return SpyAvailabilityAbstractQuery|SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdLocale($idLocale);

    /**
     * @param int $idLocale
     *
     * @return SpyAvailabilityAbstractQuery|SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale);

    /**
     * @return SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStock();

    /**
     * @return SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailability();

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStockProductQuery
     */
    public function queryStockByIdProduct($idProduct);

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function queryAllStockType();
}
