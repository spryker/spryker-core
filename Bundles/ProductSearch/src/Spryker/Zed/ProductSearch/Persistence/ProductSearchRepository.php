<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Persistence;

use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductSearch\Persistence\ProductSearchPersistenceFactory getFactory()
 */
class ProductSearchRepository extends AbstractRepository implements ProductSearchRepositoryInterface
{
    /**
     * @var string
     */
    protected const COL_COUNT = 'count';

    /**
     * Result format:
     * [
     *     $idProduct => [$idLocale => $count],
     *     ...,
     * ]
     *
     * @param array<int> $productIds
     * @param array<int> $localeIds
     *
     * @return array<int, array<int, int>>
     */
    public function getProductSearchEntitiesCountGroupedByIdProductAndIdLocale(array $productIds, array $localeIds): array
    {
        $productSearchQuery = $this->getFactory()->createProductSearchQuery()
            ->select('COUNT(*)')
            ->filterByFkProduct_In($productIds)
            ->filterByIsSearchable(true)
            ->filterByFkLocale_In($localeIds)
            ->groupByFkProduct()
            ->groupByFkLocale()
            ->select([SpyProductSearchTableMap::COL_FK_PRODUCT, SpyProductSearchTableMap::COL_FK_LOCALE])
            ->withColumn('COUNT(*)', static::COL_COUNT);

        $result = [];

        foreach ($productSearchQuery->find() as $productSearchData) {
            $idProduct = (int)$productSearchData[SpyProductSearchTableMap::COL_FK_PRODUCT];
            $idLocale = (int)$productSearchData[SpyProductSearchTableMap::COL_FK_LOCALE];
            $result[$idProduct][$idLocale] = $productSearchData[static::COL_COUNT];
        }

        return $result;
    }
}
