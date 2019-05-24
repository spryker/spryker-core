<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Persistence\Propel\Mapper;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionGroupTableMap;

class ProductOptionStorageMapper implements ProductOptionStorageMapperInterface
{
    /**
     * @param array $productOptionGroupStatuses
     * @param array $indexedProductOptionGroupStatuses
     *
     * @return array [[fkProductAbstract => [productOptionGroupName => productOptionGroupStatus]]]
     */
    public function mapProductOptionGroupStatusesToIndexedProductOptionGroupStatusesArray(array $productOptionGroupStatuses, array $indexedProductOptionGroupStatuses): array
    {
        foreach ($productOptionGroupStatuses as $productOptionGroupStatus) {
            $idProductAbstract = $productOptionGroupStatus[SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT];
            $productOptionGroupName = $productOptionGroupStatus[SpyProductOptionGroupTableMap::COL_NAME];

            $indexedProductOptionGroupStatuses[$idProductAbstract][$productOptionGroupName] = $productOptionGroupStatus[SpyProductOptionGroupTableMap::COL_ACTIVE];
        }

        return $indexedProductOptionGroupStatuses;
    }
}
