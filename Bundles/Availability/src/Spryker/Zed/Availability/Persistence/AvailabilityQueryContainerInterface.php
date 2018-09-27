<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Persistence;

interface AvailabilityQueryContainerInterface
{
    /**
     * @api
     *
     * @param string $sku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function querySpyAvailabilityBySku($sku);

    /**
     * @api
     *
     * @param string $sku
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function queryAvailabilityBySkuAndIdStore($sku, $idStore);

    /**
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function querySpyAvailabilityAbstractByAbstractSku($abstractSku);

    /**
     * @api
     *
     * @param int $idAvailabilityAbstract
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdAvailabilityAbstract($idAvailabilityAbstract, $idStore);

    /**
     * @api
     *
     * @param int $idAvailabilityAbstract
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityQuery
     */
    public function querySumQuantityOfAvailabilityAbstract($idAvailabilityAbstract, $idStore);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale, $idStore, array $stockNames = []);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale, $idStore, array $stockNames = []);

    /**
     * @api
     *
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdLocale($idLocale, $idStore, array $stockNames);

    /**
     * @api
     *
     * @param int $idLocale
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdLocale($idLocale, array $stockNames = []);

    /**
     * @api
     *
     * @param int $idLocale
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStockByIdLocale($idLocale, array $stockNames = []);

    /**
     * @api
     *
     * @param array $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailabilityWithStock(array $stockNames = []);

    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function querySpyProductAbstractAvailability();

    /**
     * @api
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAllAvailabilityAbstracts();
}
