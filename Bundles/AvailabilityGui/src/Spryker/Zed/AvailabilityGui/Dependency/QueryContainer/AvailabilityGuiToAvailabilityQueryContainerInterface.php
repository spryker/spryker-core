<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Dependency\QueryContainer;

use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;

interface AvailabilityGuiToAvailabilityQueryContainerInterface
{
    /**
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdLocale($idLocale, $idStore, array $stockNames);

    /**
     * @param int $idLocale
     * @param int $idStore
     * @param array<int> $stockIds
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithCurrentStockAndReservedProductsAggregated(
        int $idLocale,
        int $idStore,
        array $stockIds
    ): SpyProductAbstractQuery;

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale, $idStore, array $stockNames = []);

    /**
     * @param int $idProductAbstract
     * @param int $idLocale
     * @param int $idStore
     * @param array $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityAbstractWithStockByIdProductAbstractAndIdLocale($idProductAbstract, $idLocale, $idStore, array $stockNames = []);

    /**
     * @param array<int> $productAbstractIds
     * @param int $idLocale
     * @param int $idStore
     * @param array<string> $stockNames
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractWithStockByProductAbstractIds(
        array $productAbstractIds,
        int $idLocale,
        int $idStore,
        array $stockNames = []
    ): SpyProductAbstractQuery;

    /**
     * @param int $idStore
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAvailabilityAbstractByIdStore(int $idStore): SpyAvailabilityAbstractQuery;
}
